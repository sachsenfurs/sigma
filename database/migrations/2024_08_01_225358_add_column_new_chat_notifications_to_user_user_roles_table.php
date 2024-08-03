<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_user_roles', function (Blueprint $table) {
            $table->boolean("new_chat_notifications")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_user_roles', function (Blueprint $table) {
            $table->dropColumn('new_chat_notifications');
        });
    }
};
