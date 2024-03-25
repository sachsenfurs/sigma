<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("sig_favorites", function(Blueprint $table) {
           $table->foreignId("user_id")->change()->constrained()->cascadeOnDelete();
           $table->foreignId("timetable_entry_id")->change()->constrained()->cascadeOnDelete();
        });

        Schema::table("sig_timeslots", function(Blueprint $table) {
           $table->foreignId("timetable_entry_id")->change()->constrained()->cascadeOnDelete();
        });

        Schema::table("sig_reminders", function(Blueprint $table) {
            $table->foreignId("user_id")->change()->constrained()->cascadeOnDelete();
            $table->foreignId("timetable_entry_id")->change()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
