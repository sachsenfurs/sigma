<?php

use App\Enums\Necessity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sig_location_id')->nullable()->constrained()->nullOnDelete();

            $table->unsignedTinyInteger('max_user')->default(1);
            $table->string('info')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');

            $table->tinyInteger('necessity')->default(Necessity::OPTIONAL->value);

            $table->boolean('team')->default(false)->comment("shift which involves every team member (e.g. set-up, tear down, ..)");
            $table->boolean('locked')->default(false)->comment("can only changed with admin permission");
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('shifts');
    }
};
