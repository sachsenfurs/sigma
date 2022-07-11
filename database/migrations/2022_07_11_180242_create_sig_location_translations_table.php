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
        Schema::create('sig_location_translations', function (Blueprint $table) {
            $table->foreignId("sig_location_id")->constrained()->cascadeOnDelete();
            $table->string("language")->comment("Language for this particular translation entry");
            $table->string("name");
            $table->text("description");

            $table->primary([
                'sig_location_id',
                'language'
            ]);
            $table->unique([
                'sig_location_id',
                'language'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sig_location_translations');
    }
};
