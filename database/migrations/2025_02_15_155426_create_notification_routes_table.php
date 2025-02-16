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
        Schema::create('notification_routes', function (Blueprint $table) {
            $table->id();
            $table->string("notification");
            $table->unsignedBigInteger("notifiable_id");
            $table->string("notifiable_type");
            $table->json("channels")->default(json_encode([]));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_routes');
    }
};
