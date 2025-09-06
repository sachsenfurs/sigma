<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('sig_attendees', function (Blueprint $table) {
            $table->unique(['user_id', 'sig_timeslot_id']);
        });
    }

    public function down(): void {
        //
    }
};
