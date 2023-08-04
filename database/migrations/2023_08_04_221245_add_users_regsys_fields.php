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
        Schema::table('users', function (Blueprint $table) {
            $table->after("user_role_id", function(Blueprint $table) {
                $table->unsignedBigInteger("reg_id")->unique()->nullable();
                $table->string("language", 4)->nullable();
                $table->unsignedBigInteger("telegram_id")->nullable();
                $table->json("groups")->default(json_encode([]));
                $table->string("avatar")->nullable();
                $table->string("avatar_thumb")->nullable();
                $table->string("email")->nullable()->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
