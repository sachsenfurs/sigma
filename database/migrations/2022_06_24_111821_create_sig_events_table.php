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
        Schema::create('sig_events', function (Blueprint $table) {
            $table->id();
            // isConEvent (Hide HOSTs)
            $table->string("name");
            $table->foreignId("sig_host_id")->nullable()->constrained()->nullOnDelete();
            $table->string("default_language", 10)
                ->default("de")
                ->comment("defines the language for name & description, Other languages will be translated using sig_translations");
            $table->json("languages")->comment("two letter language code as JSON array")->default(json_encode([]));
            $table->text("description")->default("");
            $table->foreignId("sig_location_id")->constrained("sig_locations", "id")->cascadeOnDelete();
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
        Schema::dropIfExists('sig_events');
    }
};
