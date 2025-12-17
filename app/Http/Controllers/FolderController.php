<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        // Jika parent_id disediakan, pastikan user memiliki akses ke parent folder
        if ($request->parent_id) {
            $parent = Folder::where('user_id', Auth::id())->findOrFail($request->parent_id);
        }

        Folder::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(['success' => true, 'message' => 'Folder berhasil dibuat!']);
    }

    public function show($id)
    {
        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);
        $folders = Folder::where('user_id', Auth::id())
            ->where('parent_id', $id)
            ->get();
        $files = $folder->files;

        return view('pages.folder', compact('folder', 'folders', 'files'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);
        $folder->update(['name' => $request->name]);

        return response()->json(['success' => true, 'message' => 'Folder berhasil diubah nama!']);
    }

    public function destroy($id)
    {
        $folder = Folder::where('user_id', Auth::id())->findOrFail($id);

        // Cek apakah folder memiliki subfolder atau file
        if ($folder->children()->count() > 0 || $folder->files()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Folder tidak kosong!'], 400);
        }

        $folder->delete();

        return response()->json(['success' => true, 'message' => 'Folder berhasil dihapus!']);
    }
}
