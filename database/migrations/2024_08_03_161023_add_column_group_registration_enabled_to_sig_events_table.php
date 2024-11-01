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
        Schema::table('sig_events', function (Blueprint $table) {
            $table->boolean("group_registration_enabled")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sig_events', function (Blueprint $table) {
            $table->dropColumn('group_registration_enabled');
        });
    }
};
