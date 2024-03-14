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
            $table->after("description-en", function(Blueprint $table) {
                $table->text('additional_infos')->nullable();
                $table->boolean('fursuit_support')->default(false); //TODO: Muss später noch überarbeitet werden ist gerade aus Zeitgründen so gemacht
                $table->boolean('medic')->default(false);
                $table->boolean('security')->default(false);
                $table->boolean('other_stuff')->default(false);
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
