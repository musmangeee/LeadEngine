<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameChannelIdToBuyerChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyer_channels', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('channel_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyer_channels', function (Blueprint $table) {
            $table->dropColumn(['name','channel_id']);
        });
    }
}
