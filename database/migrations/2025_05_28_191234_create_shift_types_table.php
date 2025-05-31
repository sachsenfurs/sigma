<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void {
        Schema::create('shift_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('user_role_id')->constrained()->cascadeOnDelete();
            $table->string('color')->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shift_types');
    }
};
