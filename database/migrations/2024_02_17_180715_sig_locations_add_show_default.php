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
        Schema::table('sig_locations', function(Blueprint $table) {
            $table->after("seats", function(Blueprint $table) {
                $table->boolean('show_default')->default(false)->comment('Show in calendar view (resource view) by default?');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
