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
        Schema::create('department_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sig_event_id')->constrained('sig_events')->cascadeOnDelete();
            $table->foreignId('user_role_id')->constrained('user_roles')->cascadeOnDelete();
            $table->text('additional_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_infos');
    }
};
