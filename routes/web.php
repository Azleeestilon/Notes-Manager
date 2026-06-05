<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {

    // Registration Routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


    Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Profile
    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // Notes Management
    Route::post('/notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::post('/notes/{note}/toggle-pin', [NoteController::class, 'togglePin'])->name('notes.toggle-pin');
    Route::post('/notes/{note}/update', [NoteController::class, 'update'])->name('notes.update');
    
    // Folder Management
    Route::post('/folders/store', [FolderController::class, 'store'])->name('folders.store');
    Route::post('/notes/{note}/move-to-folder', [NoteController::class, 'moveToFolder'])->name('notes.move-to-folder');

    // Trash Management
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('/notes/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/trash/empty', [NoteController::class, 'emptyTrash'])->name('trash.empty');

    // Permanent delete routes
    Route::delete('/notes/{id}/force-delete', [NoteController::class, 'forceDelete'])->name('notes.force-delete');

    Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->name('folders.destroy');

    Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/folders/{id}/restore', [FolderController::class, 'restore'])->name('folders.restore');
    Route::delete('/folders/{id}/force-delete', [FolderController::class, 'forceDelete'])->name('folders.force-delete');

    // Halimbawa ng profile route definition
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // update password route
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    //Update settings route
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
});


