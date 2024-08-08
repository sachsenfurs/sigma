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
        Schema::table('sig_attendees', function (Blueprint $table) {
            $table->boolean("timeslot_owner")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sig_attendees', function (Blueprint $table) {
            $table->dropColumn('timeslot_owner');
        });
    }
};
