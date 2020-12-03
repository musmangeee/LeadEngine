<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSendVerticalPlatPandaPandaDeclinedToBuyers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->boolean('send_vertical')->default(0)->after('waf_key');
            $table->boolean('send_plat')->default(0)->after('waf_key');
            $table->boolean('send_plat_declined')->default(0)->after('waf_key');
            $table->boolean('send_panda')->default(0)->after('waf_key');
            $table->dropColumn('panda_channel','waf_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->boolean('panda_channel')->default(0)->after('description');
            $table->string('waf_key')->nullable();
            if (Schema::hasColumn('buyers', 'send_vertical')) {
                $table->dropColumn('send_vertical');
            }

            if (Schema::hasColumn('buyers', 'send_plat')) {
                $table->dropColumn('send_plat');
            }

            if (Schema::hasColumn('buyers', 'send_plat_declined')) {
                $table->dropColumn('send_plat_declined');
            }

            if (Schema::hasColumn('buyers', 'send_panda')) {
                $table->dropColumn('send_panda');
            }
        });
    }
}
