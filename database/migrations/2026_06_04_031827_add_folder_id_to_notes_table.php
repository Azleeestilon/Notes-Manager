<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // nullable() kasi pwedeng walang folder ang isang note (nasa "All Notes" lang)
            $table->foreignId('folder_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['folder_id']);
            $table->dropColumn('folder_id');
        });
    }
};
