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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            //$table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default("");
            $table->rememberToken();

            $table->unsignedBigInteger("reg_id")->unique()->nullable();
            $table->string("language", 4)->nullable();
            $table->unsignedBigInteger("telegram_id")->nullable();
            $table->json("groups")->default(json_encode([]));
            $table->string("avatar")->nullable();
            $table->string("avatar_thumb")->nullable();
            $table->string("email")->nullable()->change();
            $table->json("notification_channels")->default(json_encode([]));
            $table->string("telegram_user_id")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
