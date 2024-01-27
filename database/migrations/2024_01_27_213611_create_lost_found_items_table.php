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
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lassie_id')->unique();
            $table->string('image_url')->nullable();
            $table->string('thumb_url')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->char('status')->nullable()->comment("L = lost, F = found");
            $table->timestamp('lost_at')->nullable();
            $table->timestamp('found_at')->nullable();
            $table->timestamp('returned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_found_items');
    }
};
