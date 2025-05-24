<?php

use App\Enums\Approval;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('timetable_entries', function (Blueprint $table) {
            $table->tinyInteger("approval")->default(Approval::APPROVED)->comment("0 => Pending, 1 => Approved, 2 => Rejected")->after("new");
            $table->text("comment")->nullable()->after("approval");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {

    }
};
