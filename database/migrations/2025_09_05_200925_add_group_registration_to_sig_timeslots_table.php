<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('sig_timeslots', function (Blueprint $table) {
            $table->boolean("group_registration")->after("description");
        });
        Schema::table("sig_events", function (Blueprint $table) {
            $table->dropColumn("group_registration_enabled");
        });
    }

    public function down(): void {
        //
    }
};
