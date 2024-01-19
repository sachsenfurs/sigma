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
        Schema::create('artshow_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artshow-artists')->cascadeOnDelete();
            $table->string('name');
            $table->longText('description');
            $table->boolean('hide_artist')->default(false);
            $table->decimal('price',10,2);
            $table->string('imagepath');
            $table->string('winner');
            $table->boolean('paid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('artshow_items');
    }
};
