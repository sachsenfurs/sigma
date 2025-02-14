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

            $table->json("languages")->comment("two letter language code as JSON array")->default(json_encode([]));

            $table->text("description")->nullable();
            $table->text("description_en")->nullable();

            $table->boolean("text_confirmed")->default(false)->comment("Proofreading status");
            $table->boolean("no_text")->default(false)->comment("Specifies if event description is mandatory");

            $table->integer("duration")->default(0);
            $table->tinyInteger("approval")->default(\App\Enums\Approval::PENDING)->comment("0 => Pending, 1 => Approved, 2 => Rejected");

            $table->text('additional_info')->nullable();
            $table->json("attributes")->default("[]");
            $table->json('requirements')->nullable();
            $table->boolean("reg_possible")->default(false);

            $table->unsignedInteger('max_regs_per_day')->default(1);
            $table->unsignedInteger('max_group_attendees_count')->default(0);
            $table->boolean("group_registration_enabled")->default(false);

            $table->json('private_group_ids')->nullable();

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
