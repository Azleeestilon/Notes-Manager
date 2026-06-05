<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Imported for Trash feature support

class Note extends Model
{
    use SoftDeletes; // Enables the soft deletes mechanism for this model

    // Ensure 'folder_id' is included in your $fillable array!
    protected $fillable = ['title', 'content', 'user_id', 'folder_id', 'is_pinned', 'is_archived'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}