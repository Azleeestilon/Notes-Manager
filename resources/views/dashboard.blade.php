<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Notes Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark bg-opacity-95 text-light" style="min-height: 100vh;">

    @include('navbar')

    <div class="container mt-5 pt-3">
        <div class="row">
            
            {{-- SIDEBAR NAVIGATION SECTION --}}
            <div class="col-md-3 mb-4">
                <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 rounded-4 p-3 shadow">
                    <div class="p-2">
                        <h6 class="fw-bold text-uppercase text-success text-opacity-75 small mb-3" style="letter-spacing: 0.5px;">Workspace</h6>
                    </div>
                    
                    <nav class="nav flex-column mb-3">
                        <a href="{{ route('dashboard') }}" 
                           class="nav-link rounded-3 py-2 px-3 fw-medium mb-1 {{ !request('tab') || request('tab') == 'notes' ? 'text-white bg-success bg-opacity-25' : 'text-secondary text-opacity-70' }}">
                            📝 All Notes
                        </a>
                        
                        <a href="{{ route('dashboard', ['tab' => 'folders']) }}" 
                           class="nav-link rounded-3 py-2 px-3 fw-medium mb-1 {{ request('tab') == 'folders' ? 'text-white bg-success bg-opacity-25' : 'text-secondary text-opacity-70' }}">
                            📁 Folders
                        </a>
                        
                        {{-- ACTIVE FOLDERS SUBMENU LIST --}}
                        @if($folders->count() > 0)
                            <div class="ms-3 my-1 border-start border-success border-opacity-25 ps-2">
                                @foreach($folders as $f)
                                    @php 
                                        $isCurrentFolder = request('folder') == $f->id; 
                                    @endphp
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-1 rounded-2 folder-wrapper {{ $isCurrentFolder ? 'bg-success bg-opacity-10 border-start border-success border-3' : '' }}">
                                        <a href="{{ route('dashboard', ['folder' => $f->id]) }}" 
                                        class="nav-link small py-2 px-2 d-flex justify-content-between align-items-center flex-grow-1 text-truncate m-0 border-0 {{ $isCurrentFolder ? 'text-success fw-bold' : 'text-white-50' }}"
                                        style="background: transparent;">
                                            <span class="text-truncate">📁 {{ $f->name }}</span> 
                                            <span class="badge bg-secondary bg-opacity-25 text-white-50 rounded-pill ms-2" style="font-size: 0.65rem;">
                                                {{ $f->notes_count }}
                                            </span>
                                        </a>

                                        <button type="button" 
                                                class="btn btn-link text-danger text-opacity-50 hover-text-danger p-2 py-1 text-decoration-none border-0" 
                                                style="font-size: 0.75rem; background: transparent;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteFolderModal{{ $f->id }}">
                                            ✕
                                        </button>
                                    </div>

                                    {{-- SOFT DELETE FOLDER MODAL --}}
                                    <div class="modal fade" id="deleteFolderModal{{ $f->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                            <div class="modal-content bg-dark border border-danger border-opacity-25 rounded-4 shadow-lg text-light">
                                                <div class="modal-header border-bottom border-danger border-opacity-10 p-3">
                                                    <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                                        ⚠️ Move Folder to Trash?
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3 text-center">
                                                    <p class="small text-white-50 mb-0">
                                                        Are you sure you want to move the folder <strong class="text-white">"{{ $f->name }}"</strong> to the Trash Bin?<br>
                                                        <span class="text-danger small" style="font-size: 0.75rem;">*All nested notes within this folder will also be moved to trash!*</span>
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-top border-danger border-opacity-10 p-2 d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-1.5 rounded-3" style="font-size: 0.75rem;" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('folders.destroy', $f->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger px-3 rounded-3 fw-bold" style="font-size: 0.75rem;">Move to Trash</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <a href="{{ route('dashboard', ['tab' => 'trash']) }}" 
                           class="nav-link rounded-3 py-2 px-3 fw-medium {{ request('tab') == 'trash' ? 'text-white bg-danger bg-opacity-25' : 'text-danger text-opacity-75' }}">
                            🗑️ Trash Bin
                        </a>
                    </nav>
                    
                    <hr class="border-success border-opacity-25 my-2">
                    
                    <div class="p-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-2 rounded-3 fw-semibold">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MAIN INTERFACE WRAPPER PANEL --}}
            <div class="col-md-9">
                
                {{-- VIEW TRASH BIN MODULE CONTENT --}}
                @if(request('tab') == 'trash')
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div>
                            <h2 class="fw-bold mb-1">Trash <span class="text-danger text-opacity-75">Bin</span></h2>
                            <p class="text-white-50 opacity-75 small mb-0">Deleted folders and notes are kept here for safety before permanent deletion.</p>
                        </div>
                        
                        @if((isset($deletedNotes) && $deletedNotes->count() > 0) || (isset($deletedFolders) && $deletedFolders->count() > 0))
                            <button type="button" class="btn btn-outline-danger px-4 py-2 fw-bold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#emptyTrashModal">
                                🗑️ Empty Trash
                            </button>
                        @endif
                    </div>

                    {{-- DELETED FOLDERS GRID SECTION --}}
                    @if(isset($deletedFolders) && count($deletedFolders) > 0)
                        <h6 class="fw-bold text-danger text-opacity-75 text-uppercase small mb-3" style="letter-spacing: 0.5px;">Deleted Folders</h6>
                        <div class="row mb-4">
                            @foreach($deletedFolders as $df)
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-secondary bg-opacity-10 border border-danger border-opacity-25 rounded-4 text-light">
                                        <div class="card-body d-flex justify-content-between align-items-center p-3">
                                            <div class="text-truncate" style="max-width: 60%;">
                                                <h6 class="fw-bold text-danger mb-0 text-truncate">📁 {{ $df->name }}</h6>
                                                <span class="text-white-50 opacity-50 small" style="font-size: 0.7rem;">
                                                    Deleted {{ $df->deleted_at ? $df->deleted_at->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                            
                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="{{ route('folders.restore', $df->id) }}" method="POST" id="restoreFolderForm{{ $df->id }}" class="d-none">
                                                    @csrf
                                                </form>
                                                <a href="#" class="text-success text-decoration-none small fw-bold" 
                                                   onclick="event.preventDefault(); document.getElementById('restoreFolderForm{{ $df->id }}').submit();">
                                                    Restore
                                                </a>

                                                <span class="text-secondary opacity-50 small">|</span>

                                                <a href="#" class="text-danger text-decoration-none small fw-bold" 
                                                   data-bs-toggle="modal" data-bs-target="#forceDeleteFolderModal{{ $df->id }}">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FORCE DELETE FOLDER MODAL --}}
                                <div class="modal fade" id="forceDeleteFolderModal{{ $df->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content bg-dark border border-danger border-opacity-25 rounded-4 shadow-lg text-light">
                                            <div class="modal-header border-bottom border-danger border-opacity-10 p-3">
                                                <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                                    ⚠️ Permanent Delete Folder?
                                                </h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-3 text-center">
                                                <p class="small text-white-50 mb-0">
                                                    Are you sure you want to permanently delete the folder <strong class="text-white">"{{ $df->name }}"</strong>?<br>
                                                    <span class="text-danger fw-bold small" style="font-size: 0.75rem;">*WARNING: All items inside will be erased forever and cannot be recovered!*</span>
                                                </p>
                                            </div>
                                            <div class="modal-footer border-top border-danger border-opacity-10 p-2 d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-1.5 rounded-3" style="font-size: 0.75rem;" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('folders.force-delete', $df->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger px-3 rounded-3 fw-bold" style="font-size: 0.75rem;">Delete Forever</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="border-secondary border-opacity-25 mb-4">
                    @endif

                    {{-- DELETED NOTES GRID LIST --}}
                    <h6 class="fw-bold text-danger text-opacity-75 text-uppercase small mb-3" style="letter-spacing: 0.5px;">Deleted Notes</h6>
                    <div class="row">
                        @forelse($deletedNotes ?? [] as $dn)
                            <div class="col-md-4 mb-4">
                                <div class="card bg-secondary bg-opacity-10 border border-danger border-opacity-25 rounded-4 h-100 shadow-sm text-light">
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <h5 class="fw-bold mb-2 text-danger text-opacity-75 text-truncate">{{ $dn->title }}</h5>
                                            <p class="text-white-50 opacity-75 small mb-4">{{ Str::limit($dn->content, 120) }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-25 pt-2">
                                            <span class="text-white-50 opacity-50 small">
                                                Deleted {{ $dn->deleted_at ? $dn->deleted_at->diffForHumans() : 'recently' }}
                                            </span>
                                            
                                            <div class="d-flex gap-2 align-items-center">
                                                <form action="{{ route('notes.restore', $dn->id) }}" method="POST" id="restoreForm{{ $dn->id }}" class="d-none">
                                                    @csrf
                                                </form>
                                                <a href="#" class="text-success text-decoration-none small fw-bold" 
                                                onclick="event.preventDefault(); document.getElementById('restoreForm{{ $dn->id }}').submit();">
                                                    Restore
                                                </a>

                                                <span class="text-secondary opacity-50 small">|</span>

                                                <a href="#" class="text-danger text-decoration-none small fw-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#forceDeleteModal{{ $dn->id }}">
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- FORCE DELETE NOTE MODAL --}}
                            <div class="modal fade" id="forceDeleteModal{{ $dn->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content bg-dark border border-danger border-opacity-25 rounded-4 shadow-lg text-light">
                                        <div class="modal-header border-bottom border-danger border-opacity-10 p-3">
                                            <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                                                ⚠️ Permanent Delete?
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-3 text-center">
                                            <p class="small text-white-50 mb-0">
                                                Are you sure you want to permanently delete <strong class="text-white">"{{ $dn->title }}"</strong>? This action cannot be undone.
                                            </p>
                                        </div>
                                        <div class="modal-footer border-top border-danger border-opacity-10 p-2 d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-1.5 rounded-3" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('notes.force-delete', $dn->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3 rounded-3 fw-bold">Delete Forever</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @if(!isset($deletedFolders) || count($deletedFolders) == 0)
                                <div class="col-12 text-center py-5">
                                    <div class="fs-1 mb-2">🗑️</div>
                                    <h5 class="fw-medium text-secondary text-opacity-70">Your Trash Bin is empty.</h5>
                                </div>
                            @endif
                        @endforelse
                    </div>

                    {{-- PURGE ALL TRASH UTILITY MODAL --}}
                    <div class="modal fade" id="emptyTrashModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content bg-dark border border-danger border-opacity-25 rounded-4 shadow-lg text-light">
                                <div class="modal-header border-bottom border-danger border-opacity-10 p-3">
                                    <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                                        ⚠️ Wipe Out Trash?
                                    </h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-3 text-center">
                                    <p class="small text-white-50 mb-0">
                                        Are you absolutely sure? All folders and notes inside the trash will be <strong class="text-danger">permanently removed</strong> from the system forever!
                                    </p>
                                </div>
                                <div class="modal-footer border-top border-danger border-opacity-10 p-2 d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-1.5 rounded-3" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('trash.empty') }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-3 rounded-3 fw-bold">Purge All</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- VIEW FOLDERS CATEGORY SECTION --}}
                @elseif(request('tab') == 'folders')
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div>
                            <h2 class="fw-bold mb-1">My <span class="text-success text-opacity-75">Folders</span></h2>
                            <p class="text-white-50 opacity-75 small mb-0">Organize your notes into separate category folders.</p>
                        </div>
                        <button class="btn btn-success bg-gradient px-4 py-2 fw-bold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                            + New Folder
                        </button>
                    </div>

                    <div class="row">
                        @forelse($folders as $folder)
                            <div class="col-md-4 mb-4" style="cursor: pointer;" onclick="window.location='{{ route('dashboard', ['folder' => $folder->id]) }}'">
                                <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 rounded-4 shadow-sm text-light card-hover-effect">
                                    <div class="card-body p-4">
                                        <div class="fs-1 mb-2">📁</div>
                                        <h5 class="fw-bold text-white opacity-90 mb-1">{{ $folder->name }}</h5>
                                        <p class="text-white-50 opacity-50 small mb-0">{{ $folder->notes_count }} {{ Str::plural('Note', $folder->notes_count) }} inside</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="fs-1 mb-2">📁</div>
                                <h5 class="fw-medium text-secondary text-opacity-70">No folders created yet</h5>
                                <p class="text-white-50 opacity-50 small">Click the "+ New Folder" button above to get started!</p>
                            </div>
                        @endforelse
                    </div>

                {{-- GENERAL WORKSPACE ACTIONS & ACTIVE NOTES GRID --}}
                @else
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div>
                            @if(request()->has('folder'))
                                @php $currentFolder = $folders->firstWhere('id', request('folder')); @endphp
                                <h2 class="fw-bold mb-1">Folder: <span class="text-success text-opacity-75">{{ $currentFolder ? $currentFolder->name : 'Unknown' }}</span></h2>
                                <a href="{{ route('dashboard') }}" class="text-success text-decoration-none small">← Back to All Notes</a>
                            @else
                                <h2 class="fw-bold mb-1">Welcome back, <span class="text-success text-opacity-75">{{ auth()->user()->name }}</span>!</h2>
                                <p class="text-white-50 opacity-75 small mb-0">Manage your quick notes, ideas, and system tasks here.</p>
                            @endif
                        </div>
                        <button class="btn btn-success bg-gradient px-4 py-2 fw-bold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addNoteModal">
                            + New Note
                        </button>
                    </div> 

                    <div class="row">
                        @forelse($notes as $note)
                            <div class="col-md-4 mb-4">
                                <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 rounded-4 h-100 shadow-sm text-light card-hover-effect">
                                    <div class="card-body d-flex flex-column justify-content-between p-4">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex flex-column text-truncate" style="max-width: 85%;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <h5 class="fw-bold mb-0 text-white text-truncate">
                                                            {{ $note->title }}
                                                        </h5>
                                                        @if($note->is_pinned)
                                                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-2 py-1 rounded-3" style="font-size: 0.65rem;">Pinned</span>
                                                        @endif
                                                    </div>
                                                    @if($note->folder)
                                                        <span class="text-success small opacity-75 mt-1" style="font-size: 0.75rem;">📁 {{ $note->folder->name }}</span>
                                                    @endif
                                                </div>
                                                
                                                {{-- ITEM OPTIONS DROPDOWN --}}
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-white-50 p-0 text-decoration-none border-0 fs-5 lh-1 opacity-75" 
                                                            type="button" 
                                                            id="noteMenu{{ $note->id }}" 
                                                            data-bs-toggle="dropdown" 
                                                            aria-expanded="false">
                                                        ⋮ 
                                                    </button>
                                                    
                                                    <ul class="dropdown-menu dropdown-menu-end bg-dark border border-success border-opacity-25 rounded-3 shadow-lg p-2" 
                                                        aria-labelledby="noteMenu{{ $note->id }}">
                                                        
                                                        <li>
                                                            <form action="{{ route('notes.toggle-pin', $note->id) }}" method="POST" id="pinForm{{ $note->id }}" class="d-none">
                                                                @csrf
                                                            </form>
                                                            <a class="dropdown-item text-light rounded-2 py-2 small fw-medium d-flex align-items-center gap-2" 
                                                               href="#" 
                                                               onclick="event.preventDefault(); document.getElementById('pinForm{{ $note->id }}').submit();">
                                                                {{ $note->is_pinned ? '📌 Unpin Note' : '📌 Pin Note' }}
                                                            </a>
                                                        </li>
                                                        
                                                        <li>
                                                            <a class="dropdown-item text-light rounded-2 py-2 small fw-medium d-flex align-items-center gap-2" 
                                                               href="#" 
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#editNoteModal{{ $note->id }}">
                                                                ✏️ Edit Note
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item text-light rounded-2 py-2 small fw-medium d-flex align-items-center gap-2" 
                                                               href="#" 
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#moveToFolderModal{{ $note->id }}">
                                                                📁 Move to Folder
                                                            </a>
                                                        </li>
                                                        
                                                        <li><hr class="dropdown-divider border-success border-opacity-10 my-1"></li>
                                                        
                                                        <li>
                                                            <a class="dropdown-item text-danger text-opacity-75 rounded-2 py-2 small fw-medium d-flex align-items-center gap-2" 
                                                               href="#" 
                                                               data-bs-toggle="modal" 
                                                               data-bs-target="#deleteNoteModal{{ $note->id }}">
                                                                🗑️ Move to Trash
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <p class="text-white-50 small mb-4">{{ Str::limit($note->content, 120) }}</p>
                                        </div>

                                        <div class="text-white-50 opacity-50 small border-top border-secondary border-opacity-25 pt-2">
                                            Updated {{ $note->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- EDIT SPECIFIC NOTE MODAL --}}
                            <div class="modal fade" id="editNoteModal{{ $note->id }}" tabindex="-1" aria-labelledby="editNoteModalLabel{{ $note->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content bg-dark border border-success border-opacity-25 rounded-4 shadow-lg text-light">
                                        <div class="modal-header border-bottom border-success border-opacity-10 p-4">
                                            <h5 class="modal-title fw-bold" id="editNoteModalLabel{{ $note->id }}">✏️ Edit Note</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('notes.update', $note->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Note Title</label>
                                                    <input type="text" name="title" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3" value="{{ $note->title }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Content / Details</label>
                                                    <textarea name="content" rows="5" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3" required>{{ $note->content }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top border-success border-opacity-10 p-4 pt-3">
                                                <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-success bg-gradient px-4 py-2 rounded-3 fw-bold shadow-sm">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- MOVE NOTE TO FOLDER MODAL --}}
                            <div class="modal fade" id="moveToFolderModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content bg-dark border border-success border-opacity-25 rounded-4 shadow-lg text-light">
                                        <div class="modal-header border-bottom border-success border-opacity-10 p-3">
                                            <h6 class="modal-title fw-bold">📁 Select Folder</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('notes.move-to-folder', $note->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-3">
                                                <div class="mb-2">
                                                    <label class="form-label small text-white-50 mb-2">Where would you like to store this note?</label>
                                                    <select name="folder_id" class="form-select bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white rounded-3 p-2 small">
                                                        <option value="">❌ No Folder (General Area)</option>
                                                        @foreach($folders as $f)
                                                            <option value="{{ $f->id }}" {{ $note->folder_id == $f->id ? 'selected' : '' }}>📁 {{ $f->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top border-success border-opacity-10 p-2 d-flex justify-content-end gap-1">
                                                <button type="button" class="btn btn-xs text-light small opacity-75" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-sm btn-success px-3 rounded-3 fw-bold">Move</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- MOVE NOTE TO TRASH MODAL --}}
                            <div class="modal fade" id="deleteNoteModal{{ $note->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content bg-dark border border-danger border-opacity-25 rounded-4 shadow-lg text-light">
                                        <div class="modal-header border-bottom border-danger border-opacity-10 p-3">
                                            <h6 class="modal-title fw-bold text-danger d-flex align-items-center gap-2">
                                                🗑️ Move to Trash?
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-3 text-center">
                                            <p class="small text-white-50 mb-0">
                                                Do you want to send the note <strong class="text-white">"{{ $note->title }}"</strong> to the Trash Bin?
                                            </p>
                                        </div>
                                        <div class="modal-footer border-top border-danger border-opacity-10 p-2 d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-1.5 rounded-3" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3 rounded-3 fw-bold">Move</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="fs-1 mb-2">📝</div>
                                <h5 class="fw-medium text-secondary text-opacity-70">No notes found in this view</h5>
                                <p class="text-white-50 opacity-50 small">Create a brand new item using the "+ New Note" button above!</p>
                            </div>
                        @endforelse
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- CREATE GLOBAL NOTE MODAL STRUCTURE --}}
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-success border-opacity-25 rounded-4 shadow-lg text-light">
                <div class="modal-header border-bottom border-success border-opacity-10 p-4">
                    <h5 class="modal-title fw-bold" id="addNoteModalLabel">📝 Create New Note</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('notes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Note Title</label>
                            <input type="text" name="title" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3" placeholder="e.g., Capstone Database Schema" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Assign to Folder (Optional)</label>
                            <select name="folder_id" class="form-select bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3">
                                <option value="">None (General Note)</option>
                                @foreach($folders as $f)
                                    <option value="{{ $f->id }}" {{ request('folder') == $f->id ? 'selected' : '' }}>📁 {{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Content / Details</label>
                            <textarea name="content" rows="5" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3" placeholder="Type your thoughts here..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-success border-opacity-10 p-4 pt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success bg-gradient px-4 py-2 rounded-3 fw-bold shadow-sm">Save Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CREATE NEW FOLDER MODAL STRUCTURE --}}
    <div class="modal fade" id="addFolderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border border-success border-opacity-25 rounded-4 shadow-lg text-light">
                <div class="modal-header border-bottom border-success border-opacity-10 p-4">
                    <h5 class="modal-title fw-bold">📁 Create New Folder</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('folders.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-2">
                            <label class="form-label small fw-bold text-success text-opacity-75 text-uppercase">Folder Name</label>
                            <input type="text" name="name" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-3 rounded-3" placeholder="e.g., College Thesis, Sari-Sari Store" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-success border-opacity-10 p-4 pt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary text-light px-3 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-success bg-gradient px-4 py-2 rounded-3 fw-bold shadow-sm">Create Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .dropdown-menu .dropdown-item:hover {
            background-color: rgba(25, 135, 84, 0.3) !important;
            color: #ffffff !important;
        }
        .card-hover-effect:hover {
            border-color: rgba(25, 135, 84, 0.6) !important;
            transform: translateY(-2px);
            transition: all 0.2s ease-in-out;
        }
        .folder-item:hover {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>