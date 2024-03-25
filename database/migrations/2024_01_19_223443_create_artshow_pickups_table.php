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
        // Ausgabe der ersteigerten Items
        // Gewinner ist jeweils der mit dem höchsten Gebot
        Schema::create('artshow_pickups', function (Blueprint $table) {
            $table->id();

            // verknüpftes Item
            $table->foreignId("artshow_item_id")->constrained()->cascadeOnDelete();

            // verknüpfter User
            $table->foreignId("user_id")->constrained()->restrictOnDelete();

            // freitext feld für etwaige Infos, die das DD/AS-Team bei der Ausgabe noch mit reinschreiben kann
            $table->text("info")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artshow_pickups');
    }
};
