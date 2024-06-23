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
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('user_user_roles', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('user_role_permissions', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->dropForeign('user_role_permissions_permission_id_foreign');
            $table->dropColumn('permission_id');
            $table->string("permission")->after("user_role_id");
            $table->unsignedInteger("level")->default(0);
        });

        Schema::drop('permissions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
