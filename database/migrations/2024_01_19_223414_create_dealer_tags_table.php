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
        // Tags für die Dealer (Merch, Prints, Shirts, usw.)
        Schema::create('dealer_tags', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("name_en")->nullable();
        });

        Schema::create('dealer_dealer_tag', function(Blueprint $table) {
            $table->id();
            $table->foreignId("dealer_id")->constrained()->cascadeOnDelete();
            $table->foreignId("dealer_tag_id")->constrained()->cascadeOnDelete();
            $table->unique([ "dealer_id", "dealer_tag_id" ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_tags');
    }
};
