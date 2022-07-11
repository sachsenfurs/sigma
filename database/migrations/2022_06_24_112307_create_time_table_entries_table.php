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
            $table->foreignId("sig_event_id")->constrained()->cascadeOnDelete();
            $table->foreignId("sig_location_id")->nullable()->constrained()->nullOnDelete();
            $table->dateTime("start");
            $table->dateTime("end");
            $table->boolean("cancelled")->default(false);
            $table->foreignId("replaced_by_id")->nullable()->constrained($table->getTable(), "id")->nullOnDelete();
            $table->boolean("hide")->default(false);
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
