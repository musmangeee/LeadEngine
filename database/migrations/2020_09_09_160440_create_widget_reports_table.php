<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('total_lead')->default(0);
            $table->bigInteger('total_accepted')->default(0);
            $table->bigInteger('total_rejected')->default(0);
            $table->bigInteger('total_error')->default(0);
            $table->bigInteger('total_redirect')->default(0);
            $table->bigInteger('total_redirect_success')->default(0);
            $table->bigInteger('total_redirect_error')->default(0);
            $table->bigInteger('total_redirect_duration')->default(0);
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
        Schema::dropIfExists('widget_reports');
    }
}
