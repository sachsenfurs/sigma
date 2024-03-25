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

            $table->string('title');
            $table->string('fore_color')->default('#333333');
            $table->string('border_color')->default('#666666');
            $table->string('background_color')->default('#E6E6E6');

            $table->boolean('perm_manage_settings')->default(false);
            $table->boolean('perm_manage_users')->default(false);
            $table->boolean('perm_manage_events')->default(false);
            $table->boolean('perm_manage_locations')->default(false);
            $table->boolean('perm_manage_hosts')->default(false);

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
        Schema::dropIfExists('user_roles');
    }
};
