<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceToBuyerPandaChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_panda_channels', function (Blueprint $table) {
            $table->decimal('price',15,2)->default(0)->after('portfolio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_panda_channels', function (Blueprint $table) {
            $table->dropColumn(['price']);
        });
    }
}
