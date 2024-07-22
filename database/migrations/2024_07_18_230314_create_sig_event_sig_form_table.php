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
        Schema::create('sig_event_sig_form', function (Blueprint $table) {
            $table->id();
            $table->foreignId("sig_event_id")->constrained()->cascadeOnDelete();
            $table->foreignId("sig_form_id")->constrained()->cascadeOnDelete();
        });

        Schema::table("sig_forms", function (Blueprint $table) {
            $table->dropConstrainedForeignId("sig_event_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sig_event_sig_form');
    }
};
