<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponsesFieldToIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->longText('status_response')->after('wait_timeout')->nullable();
            $table->longText('message_response')->after('status_response')->nullable();
            $table->longText('price_response')->after('message_response')->nullable();
            $table->longText('redirect_response')->after('price_response')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->dropColumn(['status_response','message_response','price_response','redirect_response']);
        });
    }
}
