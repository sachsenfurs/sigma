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
        Schema::table('post_channels', function (Blueprint $table) {
            $table->bigInteger("test_channel_identifier")->after("channel_identifier")->nullable();
            $table->text("info")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_channels', function (Blueprint $table) {
            //
        });
    }
};
