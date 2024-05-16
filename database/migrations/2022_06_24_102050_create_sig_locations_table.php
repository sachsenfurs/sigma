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
        Schema::create('sig_locations', function (Blueprint $table) {
            $table->id();

            // base data
            $table->string("name");
            $table->string("name_en")->nullable();
            $table->string("description")->default("");
            $table->string('description_en')->nullable()->default("");

            // physical data
            $table->string("floor")->nullable();
            $table->string("room")->nullable();
            $table->string("roomsize")->nullable();
            $table->string("seats")->nullable();

            // signage
            $table->json("render_ids")->nullable()
                  ->comment("layer id for displaying as interactive SVG or whatever");
            $table->boolean("infodisplay")->default(false)
                  ->comment("Is there a digital display in front of the door? (Signage)");

            $table->boolean("essential")->default(false)
                ->comment("true = show periodically on the info screens (signage)");
            $table->text("essential_description")->nullable();
            $table->text("essential_description_en")->nullable();

            $table->boolean('show_default')->default(false)
                ->comment('Show in calendar view (resource view) by default?');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sig_locations');
    }
};
