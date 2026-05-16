<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('post_channels', function (Blueprint $table) {
            $table->string('channel_identifier')->change();
            $table->string('test_channel_identifier')->nullable()->change();
            $table->string('implementation')->default('telegram')->nullable(false)->change();

            $table->unique(['implementation', 'channel_identifier'], 'post_channels_implementation_identifier_unique');
            $table->unique(['implementation', 'test_channel_identifier'], 'post_channels_implementation_test_identifier_unique');
        });

        Schema::table('post_channel_messages', function (Blueprint $table) {
            $table->string('message_id')->change();
        });
    }

    public function down(): void {
        //
    }
};
