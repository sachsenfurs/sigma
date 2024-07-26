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
        Schema::table('sig_events', function (Blueprint $table) {
            $table->boolean("text_confirmed")->default(false)->after("description_en")->comment("Proofreading status");
            $table->boolean("no_text")->default(false)->after("text_confirmed")->comment("Specifies if event description is mandatory");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sig_events', function (Blueprint $table) {
            //
        });
    }
};
