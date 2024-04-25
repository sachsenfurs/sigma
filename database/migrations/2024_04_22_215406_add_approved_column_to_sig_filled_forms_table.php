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
        Schema::table('sig_filled_forms', function (Blueprint $table) {
            // Approved:
            // 0 - Pending
            // 1 - Approved
            // 2 - Rejected
            $table->tinyInteger('approved', false, true)->default(0)->after('user_id');
            $table->string('rejection_reason')->nullable()->after('approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sig_filled_forms', function (Blueprint $table) {
            $table->dropColumn('approved');
            $table->dropColumn('rejection_reason');
        });
    }
};
