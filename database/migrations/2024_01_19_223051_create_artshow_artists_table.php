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

        // Künstler für die Artshow (nicht für den DD)
        Schema::create('artshow_artists', function (Blueprint $table) {
            $table->id();

            // verknüpfter User. Optional. Wird bei der Anmeldung automatisch verknüpft (wenn der Gast sich für die Artshow anmeldet)
            // Optional soll man aber auch Artists anlegen können, welche ggf. nicht auf der Con als Teilnehmer registriert sind bzw. sich erst später registrieren
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Name des Künstlers wie er erwähnt werden möchte
            $table->string('name')->nullable();

            $table->string('social')->nullable()->comment('Twitter, FA, Gallery, etc.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artshow_artists');
    }
};
