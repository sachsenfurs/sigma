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
        // Anmeldung der Dealer
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();

            // Künstlername der im Conbook stehen soll
            $table->string("name");

            // verknüpfter User (Auch hier optional und wie bei den Artists verfahren)
            $table->foreignId("user_id")->nullable()->constrained()->nullOnDelete();

            // Beschreibung fürs Conbook
            $table->text("info")->nullable();
            $table->text("info_en")->nullable();

            // Link zur Galerie o.ä.
            $table->string("gallery_link")->nullable();
            $table->smallInteger("space")->default(1);

            //Kontaktmöglichkeiten
            $table->string("contact_way")->nullable();
            $table->string("contact")->nullable();


            // Icon fürs Conbook (relativer Pfad, local storage)
            $table->string("icon_file")->nullable();

            $table->boolean("approved")->default(false);

            // in welchem Raum sitzt der Künstler später?
            $table->foreignId("sig_location_id")->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
