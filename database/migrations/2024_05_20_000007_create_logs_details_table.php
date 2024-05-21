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
        Schema::create('logs_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('log_id');
            $table->string('field', 150);
            $table->string('field_description', 150);
            $table->string('old_value', 500)->nullable();
            $table->string('curr_value', 500)->nullable();

            #FK
            $table->foreign('log_id')->references('id')->on('logs')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_details');
    }
};
