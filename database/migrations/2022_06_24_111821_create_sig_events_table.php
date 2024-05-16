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
            $table->string('name_en')->nullable();

            $table->foreignId("sig_host_id")->nullable()->constrained()->nullOnDelete();
            $table->json("languages")->comment("two letter language code as JSON array")->default(json_encode([]));

            $table->text("description")->nullable();
            $table->text("description_en")->nullable();

            $table->integer("duration")->default(0);
            $table->boolean('approved')->default(1);
            $table->text('additional_info')->nullable();
            $table->json('requirements')->nullable();
            $table->boolean("reg_possible")->default(false);

            $table->unsignedInteger('max_regs_per_day')->default(1);
            $table->unsignedInteger('max_group_attendees_count')->default(0);

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
