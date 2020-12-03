<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourlyCapBuyerDeliverySchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_delivery_schedules', function (Blueprint $table) {
            $table->integer('hourly_cap')->default(0)->after('daily_cap');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_delivery_schedules', function (Blueprint $table) {
            $table->dropColumn('hourly_cap');
        });
    }
}
