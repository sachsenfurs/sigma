<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('page_hooks', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->text("content")->default("");
            $table->text("content_en")->nullable();
            $table->boolean("html")->default(false);
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('page_hooks');
    }
};
