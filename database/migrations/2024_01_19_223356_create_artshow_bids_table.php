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
        // Gebote für die Artshow
        Schema::create('artshow_bids', function (Blueprint $table) {
            $table->id();

            // verknüpftes Item
            $table->foreignId("artshow_item_id")->constrained()->cascadeOnDelete();

            // Wert des Gebots
            $table->decimal("value")->default(0);

            // Wer hat geboten? (User zwingend erforderlich)
            $table->foreignId("user_id")->constrained()->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artshow_bids');
    }
};
