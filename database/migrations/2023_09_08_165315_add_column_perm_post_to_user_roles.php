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
        Schema::table('user_roles', function (Blueprint $table) {
            Schema::table('user_roles', function (Blueprint $table) {
                $table->after("perm_manage_hosts", function(Blueprint $table) {
                    $table->boolean("perm_post")->default(false);
                });
            });
        });

        DB::table("user_roles")
            ->where("title", "Administrator")
            ->orWhere("title", "Leitstelle")
            ->update([
                'perm_post' => 1
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
