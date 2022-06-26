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
        Schema::create('time_table_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sig_event")->constrained()->cascadeOnDelete();
            $table->foreignId("sig_location")->nullable()->constrained()->nullOnDelete();
            $table->string("name")->default("");
            $table->dateTime("start");
            $table->dateTime("end");
            $table->boolean("cancelled")->default(false);
            $table->foreignId("replaced_by")->nullable()->constrained($table->getTable(), "id")->nullOnDelete();
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
        Schema::dropIfExists('time_tables');
    }
};
