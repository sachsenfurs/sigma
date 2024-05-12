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
        Schema::create('info_socials', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("name_en")->nullable();
            $table->string("link");
            $table->string("link_en")->nullable();
            $table->string("icon")->nullable();
            $table->text("qr")->nullable();
            $table->text("qr_en")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_socials');
    }
};
