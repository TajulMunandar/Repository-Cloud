<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->is_admin == 0) {
            // kalau admin, ambil semua file
            $files = File::where('user_id', Auth::id())->get();
        } else {
            // kalau bukan admin, ambil file milik user login saja
            $files = File::all();
        }
        return view('pages.file', compact('files'));
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
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store('uploads', 'public');

        // hitung durasi & bandwidth dari client (kalau dikirim via AJAX)
        $uploadDuration = $request->input('upload_duration'); // detik
        $uploadBw = $request->input('upload_bw'); // KB/s

        File::create([
            'user_id' => Auth::id(),
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

        return Storage::disk('public')->download($file->file_path, $file->file_name);
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
        $file = File::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);

        // hapus fisik
        Storage::disk('public')->delete($file->file_path);

        // hapus permanen record
        $file->forceDelete();

        return redirect()->route('files.trash')->with('success', 'File berhasil dihapus permanen!');
    }
}
