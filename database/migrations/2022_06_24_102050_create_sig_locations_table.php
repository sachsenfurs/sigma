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
            $table->string("name");
            $table->string("description")->default("");
            $table->json("render_ids")->nullable()
                ->comment("layer id for displaying as interactive SVG or whatever");
            $table->string("floor")->nullable();
            $table->string("room")->nullable();
            $table->boolean("infodisplay")->default(false)
                ->comment("Is there a digital display in front of the door? (Signage)");
            // just an idea
            $table->string("roomsize")->nullable();
            $table->string("seats")->nullable();
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
