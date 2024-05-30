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
        Schema::create('sig_tags', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique()->comment("Internal name, used for internal automation (eg. 'signup')");
            $table->string("description")->nullable();
            $table->string("description_en")->nullable();
            $table->string("icon")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sig_tags');
    }
};
