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
        // Gegenstände für die Artshow
        Schema::create('artshow_items', function (Blueprint $table) {
            $table->id();

            // Fremdschlüssel zum angelegten Artist
            $table->foreignId('artshow_artist_id')->constrained()->cascadeOnDelete();

            // Name des Items
            $table->string('name')->nullable();

            // Beschreibung für die Ausstellung
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();

            // Startgebot
            $table->decimal('starting_bid')->default(0);

            // Charityanteil in Prozent
            $table->decimal('charity_percentage')->default(0);

            // interne Notizen
            $table->text('additional_info')->nullable()->comment('only visible for adminstration/auctioner');

            // relativer Pfad zur Datei (local storage)
            $table->string('image')->nullable();

            // Nur Ausstellungstück oder zur Auktion freigegeben?
            $table->boolean('auction')->default(true);

            // Status, ob es nach der Anmeldung "angenommen" wurde
            $table->tinyInteger("approval")->default(\App\Models\Ddas\Enums\Approval::PENDING)->comment("0 => Pending, 1 => Approved, 2 => Rejected");

            // Erfolgreich verkauft?
            $table->boolean('sold')->default(false);

            // Bezahlt?
            $table->boolean('paid')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artshow_items');
    }
};

