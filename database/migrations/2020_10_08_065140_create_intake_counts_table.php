<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntakeCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_counts', function (Blueprint $table) {
            $table->id();
            $table->string('countable_type');
            $table->string('countable_id');
            $table->date('intake_date');
            $table->bigInteger('hour_00')->default(0);
            $table->bigInteger('hour_01')->default(0);
            $table->bigInteger('hour_02')->default(0);
            $table->bigInteger('hour_03')->default(0);
            $table->bigInteger('hour_04')->default(0);
            $table->bigInteger('hour_05')->default(0);
            $table->bigInteger('hour_06')->default(0);
            $table->bigInteger('hour_07')->default(0);
            $table->bigInteger('hour_08')->default(0);
            $table->bigInteger('hour_09')->default(0);
            $table->bigInteger('hour_10')->default(0);
            $table->bigInteger('hour_11')->default(0);
            $table->bigInteger('hour_12')->default(0);
            $table->bigInteger('hour_13')->default(0);
            $table->bigInteger('hour_14')->default(0);
            $table->bigInteger('hour_15')->default(0);
            $table->bigInteger('hour_16')->default(0);
            $table->bigInteger('hour_17')->default(0);
            $table->bigInteger('hour_18')->default(0);
            $table->bigInteger('hour_19')->default(0);
            $table->bigInteger('hour_20')->default(0);
            $table->bigInteger('hour_21')->default(0);
            $table->bigInteger('hour_22')->default(0);
            $table->bigInteger('hour_23')->default(0);
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
        Schema::dropIfExists('intake_counts');
    }
}
