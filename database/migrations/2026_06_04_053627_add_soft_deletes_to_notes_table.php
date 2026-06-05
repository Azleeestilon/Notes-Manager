<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('notes', function (Blueprint $blueprint) {
            $blueprint->softDeletes(); // Ito ang magdadagdag ng 'deleted_at' column sa notes table
        });
    }


    public function down(): void
    {
        Schema::table('notes', function (Blueprint $blueprint) {
            $blueprint->dropSoftDeletes(); // Pantanggal kung i-ro-roll back ang migration
        });
    }
};