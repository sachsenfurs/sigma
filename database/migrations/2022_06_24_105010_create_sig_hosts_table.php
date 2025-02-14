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
        Schema::create('sig_hosts', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger('reg_id')->nullable();

            $table->text("description")->nullable()->default("");
            $table->string('description_en')->nullable()->default("");
            $table->string('color')->nullable();
            $table->boolean("hide")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sig_hosts');
    }
};
