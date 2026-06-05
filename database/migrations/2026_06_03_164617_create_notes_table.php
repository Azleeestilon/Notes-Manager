<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
        $table->id();
        // Ikokonekta natin ang note sa kung sinong user ang gumawa nito
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('content');
        // Para sa trash bin at folder logic natin sa susunod:
        $table->string('folder')->nullable(); // null kapag nasa "All Notes"
        $table->boolean('is_archived')->default(false); // true kapag nasa Trash Bin
        $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
