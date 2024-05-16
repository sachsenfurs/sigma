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
            $table->string("description");
            $table->string("description_en")->nullable();
            $table->string("link");
            $table->string("link_en")->nullable();
            $table->string("link_name")->nullable();
            $table->string("link_name_en")->nullable();
            $table->string("icon")->nullable();
            $table->text("qr")->nullable();
            $table->text("qr_en")->nullable();
            $table->json("show_on")->default(json_encode([]));
            $table->integer("order")->default(0);
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
