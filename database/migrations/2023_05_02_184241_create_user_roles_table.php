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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('fore_color')->default('#333333');
            $table->string('border_color')->default('#666666');
            $table->string('background_color')->default('#E6E6E6');
            $table->string('registration_system_key')->nullable();
            $table->boolean("chat_activated")->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
};
