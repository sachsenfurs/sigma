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
            $table->after("description_en", function(Blueprint $table) {
                $table->text('additional_infos')->nullable();
                $table->text('fursuit_support')->default("0"); //TODO: Muss später noch überarbeitet werden ist gerade aus Zeitgründen so gemacht
                $table->text('medic')->default("0");
                $table->text('security')->default("0");
                $table->text('other_stuff')->default("0");
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sig_events', function (Blueprint $table) {
            //
        });
    }
};
