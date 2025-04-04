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
        Schema::create('post_channels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("channel_identifier");
            $table->bigInteger("test_channel_identifier")->nullable();
            $table->text("info")->nullable();
            $table->string("name")->nullable();
            $table->string("language")->default("de");
            $table->string("implementation")->nullable();
            $table->boolean("default")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_channels');
    }
};
