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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("notifiable_id");
            $table->string("notifiable_type");

            $table->unsignedBigInteger("remindable_id");
            $table->string("remindable_type");

            $table->dateTime("send_at");
            $table->integer("offset_minutes")->default(0);
            $table->unsignedInteger("sent_at")->nullable()->comment("unix timestamp");

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reminders');
    }
};
