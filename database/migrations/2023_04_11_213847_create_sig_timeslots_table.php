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
        Schema::create('sig_timeslots', function (Blueprint $table) {
            $table->id();

            $table->foreignId('timetable_entry_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('max_users')->default(1);
            $table->dateTime('slot_start');
            $table->dateTime('slot_end');
            $table->dateTime('reg_start')->nullable();
            $table->dateTime('reg_end')->nullable();
            $table->text('description')->nullable();
            $table->boolean('self_register')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sig_timeslots');
    }
};
