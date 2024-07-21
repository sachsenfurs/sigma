<?php

use App\Models\SigTimeslot;
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
        Schema::table('sig_timeslots', function (Blueprint $table) {
            $table->dateTime('slot_start')->change();
            $table->dateTime('slot_end')->change();
            $table->boolean('self_register')->default(true);
        });

        SigTimeslot::all()->each(function($slot) {
            $slot->update([
                'slot_start' => $slot->timetableEntry->start->setTime($slot->slot_start->hour, $slot->slot_start->minute),
                'slot_end' => $slot->timetableEntry->end->setTime($slot->slot_end->hour, $slot->slot_end->minute),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
