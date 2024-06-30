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
        Schema::create('post_channel_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId("post_id")->constrained()->cascadeOnDelete();
            $table->foreignId("post_channel_id")->constrained()->cascadeOnDelete();
            $table->bigInteger('message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_channel_message');
    }
};
