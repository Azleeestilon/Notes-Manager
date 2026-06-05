<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nagdagdag ng settings na may default values para sa bagong registers
            $table->boolean('desktop_notifications')->default(true)->after('profile_image');
            $table->boolean('alert_sounds')->default(false)->after('desktop_notifications');
            $table->integer('auto_delete_interval')->default(30)->after('alert_sounds'); // 30 days default
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['desktop_notifications', 'alert_sounds', 'auto_delete_interval']);
        });
    }
};