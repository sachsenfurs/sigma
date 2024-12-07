<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sig_events', function (Blueprint $table) {
            $table->json('private_group_ids')->nullable()->after('max_group_attendees_count');
        });
    }
};
