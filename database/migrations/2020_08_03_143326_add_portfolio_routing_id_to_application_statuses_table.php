<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortfolioRoutingIdToApplicationStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_statuses', function (Blueprint $table) {
            $table->bigInteger('portfolio_routing_id')->after('id')->unsigned()->nullable();
            $table->foreign('portfolio_routing_id')->references('id')->on('portfolio_routings')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_statuses', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            $table->dropColumn('portfolio_routing_id');
            Schema::enableForeignKeyConstraints();
        });
    }
}
