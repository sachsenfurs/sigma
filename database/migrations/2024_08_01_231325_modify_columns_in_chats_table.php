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
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn('department');
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->foreignId('user_role_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn('user_role_id');
        });

        Schema::table('chats', function (Blueprint $table) {
            $table->enum('department', ['artshow', 'dealersden', 'events'])->change();
        });
    }
};
