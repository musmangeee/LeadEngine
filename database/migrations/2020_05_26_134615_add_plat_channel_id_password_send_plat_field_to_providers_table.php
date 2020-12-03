<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlatChannelIdPasswordSendPlatFieldToProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->boolean('send_plat')->default(0)->after('waf_key');
            $table->string('plat_channel_id')->nullable()->after('waf_key');
            $table->string('plat_channel_password')->nullable()->after('waf_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['send_plat','plat_channel_id','plat_channel_password']);
        });
    }
}
