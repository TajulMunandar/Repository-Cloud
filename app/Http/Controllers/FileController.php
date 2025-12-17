<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileActivity;
use App\Models\FileShare;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $folderId = $request->get('folder_id');

        if (Auth::user()->is_admin == 1) {
            // Admin melihat semua
            $folders = $folderId
                ? Folder::where('parent_id', $folderId)->get()  // Subfolders dari folder yang dibuka
                : Folder::whereNull('parent_id')->get();        // Root folders
            $files = $folderId
                ? File::where('folder_id', $folderId)->get()
                : File::whereNull('folder_id')->get();
        } else {
            // User biasa
            $folders = $folderId
                ? Folder::where('user_id', Auth::id())->where('parent_id', $folderId)->get()
                : Folder::where('user_id', Auth::id())->whereNull('parent_id')->get();
            $files = $folderId
                ? File::where('user_id', Auth::id())->where('folder_id', $folderId)->get()
                : File::where('user_id', Auth::id())->whereNull('folder_id')->get();
        }

        $currentFolder = $folderId ? Folder::find($folderId) : null;

        return view('pages.file', compact('files', 'folders', 'currentFolder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:1024000', // max 100MB
            'expired_date' => 'nullable|date',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        // Jika folder_id disediakan, pastikan user memiliki akses
        if ($request->folder_id) {
            $folder = Folder::where('user_id', Auth::id())->findOrFail($request->folder_id);
        }

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        // hitung durasi & bandwidth dari client (kalau dikirim via AJAX)
        $uploadDuration = $request->input('upload_duration'); // detik
        $uploadBw = $request->input('upload_bw'); // KB/s

        File::create([
            'user_id' => Auth::id(),
            'folder_id' => $request->folder_id,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $uploadedFile->getClientMimeType(),
            'file_size' => $uploadedFile->getSize(),
            'expired_date' => $request->expired_date,
            'upload_bw' => $uploadBw ?? null,
            'upload_duration' => $uploadDuration ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'File berhasil diupload!']);
    }

    public function updateUploadStats(Request $request)
    {
        $file = File::where('file_name', $request->file_name)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($file) {
            $file->update([
                'upload_duration' => $request->upload_duration,
                'upload_bw' => $request->upload_bw,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $file = File::with('user')->findOrFail($id);

        // catat aktivitas view
        $file->increment('total_views');
        FileActivity::create([
            'file_id' => $file->id,
            'user_id' => Auth::id(),
            'activity_type' => 'view'
        ]);

        return view('pages.show', compact('file'));
    }

    public function view($id)
    {
        $file = File::findOrFail($id);
        $file->increment('total_views');

        FileActivity::create([
            'file_id' => $file->id,
            'user_id' => Auth::id(),
            'activity_type' => 'view'
        ]);

        return response()->file(storage_path('app/public/' . $file->file_path));
    }

    public function download($id)
    {
        $file = File::findOrFail($id);
        $file->increment('total_downloads');

        FileActivity::create([
            'file_id' => $file->id,
            'user_id' => Auth::id(),
            'activity_type' => 'download'
        ]);

        return response()->download(storage_path('app/public/' . $file->file_path), $file->file_name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $file = File::findOrFail($id);
        $file->delete(); // soft delete, file fisik masih ada

        return redirect()->back()->with('success', 'File dipindahkan ke Riwayat (Trash)!');
    }

    public function trash()
    {
        $files = File::onlyTrashed()
            ->where('user_id', auth::id())
            ->get();

        return view('pages.trash', compact('files'));
    }

    public function restore($id)
    {
        $file = File::onlyTrashed()->where('user_id', auth::id())->findOrFail($id);
        $file->restore();

        return redirect()->route('files.trash')->with('success', 'File berhasil direstore!');
    }

    public function forceDelete($id)
    {
        $file = File::onlyTrashed()->where('user_id', Auth::id())->findOrFail($id);

        // hapus fisik
        Storage::disk('public')->delete($file->file_path);

        // hapus permanen record
        $file->forceDelete();

        return redirect()->route('files.trash')->with('success', 'File berhasil dihapus permanen!');
    }

    public function rename(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required|string|max:255'
        ]);

        $file = File::where('user_id', Auth::id())->findOrFail($id);
        $file->update(['file_name' => $request->file_name]);

        return response()->json(['success' => true, 'message' => 'File berhasil diubah nama!']);
    }

    public function share(Request $request, $id)
    {
        $request->validate([
            'shared_with' => 'required|exists:users,id',
            'permission' => 'required|in:view,download',
            'expires_at' => 'nullable|date|after:now'
        ]);

        $file = File::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah sudah di-share dengan user ini
        $existingShare = FileShare::where('file_id', $file->id)
            ->where('shared_with', $request->shared_with)
            ->first();

        if ($existingShare) {
            $existingShare->update([
                'permission' => $request->permission,
                'expires_at' => $request->expires_at
            ]);
        } else {
            FileShare::create([
                'file_id' => $file->id,
                'shared_by' => Auth::id(),
                'shared_with' => $request->shared_with,
                'permission' => $request->permission,
                'expires_at' => $request->expires_at
            ]);
        }

        return response()->json(['success' => true, 'message' => 'File berhasil dibagikan!']);
    }

    public function getSharedFiles()
    {
        $sharedFiles = FileShare::with(['file', 'sharedBy'])
            ->where('shared_with', Auth::id())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        return view('pages.shared', compact('sharedFiles'));
    }
}
