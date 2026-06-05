<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'notes');

        // 1. Get all user folders along with the active notes count for the sidebar and tabs
        $folders = $user->folders()->withCount(['notes' => function ($query) {
            $query->where('is_archived', false); // Only count notes that are not archived or in trash
        }])->get();

        // Initialize default collections to prevent errors in the Blade template view
        $notes = collect();
        $deletedNotes = collect();
        $deletedFolders = collect(); // Added for Deleted Folders support

        // 2. Logic to filter notes depending on the active Tab or selected Folder
        if ($tab == 'trash') {
            // IF TRASH TAB: Fetch soft-deleted notes belonging to the user
            $deletedNotes = Note::onlyTrashed()
                                ->where('user_id', $user->id)
                                ->latest()
                                ->get();
                                
            // STEP 4 IMPLEMENTATION: Fetch soft-deleted folders belonging to the user as well
            $deletedFolders = $user->folders()
                                   ->onlyTrashed()
                                   ->latest()
                                   ->get();

        } elseif ($request->has('folder')) {
            // IF A SPECIFIC FOLDER IS SELECTED: Show only active notes linked to that folder
            $notes = $user->notes()
                          ->where('is_archived', false)
                          ->where('folder_id', $request->get('folder'))
                          ->orderBy('is_pinned', 'desc')
                          ->latest()
                          ->get();
        } else {
            // DEFAULT VIEW (All Notes): Show all active notes belonging to the user
            $notes = $user->notes()
                          ->where('is_archived', false)
                          ->orderBy('is_pinned', 'desc')
                          ->latest()
                          ->get();
        }

        // Pass all required resources to dashboard.blade.php including $deletedNotes and $deletedFolders
        return view('dashboard', compact('notes', 'folders', 'deletedNotes', 'deletedFolders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        Auth::user()->notes()->create([
            'title' => $request->title,
            'content' => $request->content,
            'folder_id' => $request->folder_id,
            'is_archived' => false,
        ]);

        if ($request->filled('folder_id')) {
            return redirect()->route('dashboard', ['folder' => $request->folder_id]);
        }

        return redirect()->route('dashboard');
    }

    public function togglePin(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->update([
            'is_pinned' => !$note->is_pinned
        ]);

        return redirect()->back();
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }

    public function moveToFolder(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $note->update([
            'folder_id' => $request->folder_id
        ]);

        return redirect()->back()->with('success', 'Note moved successfully!');
    }

    /**
     * Move a note to the Trash Bin (Soft Delete)
     */
    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete(); // Ensure your Note Model imports and uses the "SoftDeletes" trait!
        
        return redirect()->route('dashboard', ['tab' => 'notes'])->with('success', 'Note moved to trash.');
    }

    /**
     * Restore a note from the Trash Bin
     */
    public function restore($id)
    {
        $note = Note::withTrashed()->where('user_id', Auth::id())->findOrFail($id);
        $note->restore();
        
        return redirect()->route('dashboard', ['tab' => 'trash'])->with('success', 'Note restored successfully.');
    }

    /**
     * Permanently delete all trash bin contents belonging to the user
     */
    public function emptyTrash()
    {
        Note::onlyTrashed()->where('user_id', Auth::id())->forceDelete();
        
        return redirect()->route('dashboard', ['tab' => 'trash'])->with('success', 'Trash bin emptied.');
    }
    
    /**
     * Permanently delete a specific note from the trash bin
     */
    public function forceDelete($id)
    {
        $note = Note::where('user_id', auth()->id())->onlyTrashed()->findOrFail($id);
        $note->forceDelete();

        return redirect()->back()->with('success', 'Note permanently deleted.');
    }
}