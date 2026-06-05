<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Auth::user()->folders()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('dashboard')->with('success', 'Folder created successfully!');
    }

    /**
     * Move the folder and all its notes to the Trash Bin (Soft Delete)
     */
    public function destroy($id)
    {
        $folder = Auth::user()->folders()->findOrFail($id);

        // Soft delete all notes contained within this folder as well
        $folder->notes()->delete();

        // Soft delete the folder itself
        $folder->delete();

        return redirect()->route('dashboard')->with('success', 'Folder and its notes moved to trash.');
    }

    /**
     * Permanently delete the folder and all its notes (Force Delete)
     */
    public function forceDelete($id)
    {
        // Find from within the soft-deleted folders
        $folder = Auth::user()->folders()->onlyTrashed()->findOrFail($id);

        // Permanently delete the notes inside that were also soft-deleted
        $folder->notes()->onlyTrashed()->forceDelete();

        // Permanently delete the folder itself
        $folder->forceDelete();

        return redirect()->route('dashboard', ['tab' => 'trash'])->with('success', 'Folder and its contents permanently deleted.');
    }

    /**
     * Restore the folder along with all the notes inside it
     */
    public function restore($id)
    {
        $folder = Auth::user()->folders()->onlyTrashed()->findOrFail($id);
        $folder->restore();

        // Restore the notes that were deleted together with this folder
        $folder->notes()->onlyTrashed()->restore();

        return redirect()->route('dashboard', ['tab' => 'trash'])->with('success', 'Folder and its notes restored successfully.');
    }
}