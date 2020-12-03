<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanUpProviderUnnecessaryColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['waf_key','plat_channel_id','plat_channel_password','panda_channel','send_plat']);
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
            $table->string('waf_key')->nullable()->after('description');
            $table->string('plat_channel_id')->nullable()->after('description');
            $table->string('plat_channel_password')->nullable()->after('description');
            $table->boolean('panda_channel')->default(0)->after('description');
            $table->boolean('send_plat')->default(0)->after('description');
        });
    }
}
