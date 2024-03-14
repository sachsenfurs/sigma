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
        Schema::table('sig_hosts', function (Blueprint $table) {
            $table->after("description", function(Blueprint $table) {
                $table->string('description_en')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sighosts', function (Blueprint $table) {
            //
        });
    }
};
