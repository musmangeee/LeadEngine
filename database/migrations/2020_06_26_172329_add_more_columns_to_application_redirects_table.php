<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToApplicationRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_redirects', function (Blueprint $table) {
            $table->dropColumn('is_success');
            $table->integer('redirect_count')->after('redirect_url')->default(0);
            $table->string('referer_url',255)->nullable()->after('redirect_count');
            $table->string('referer_agent',255)->nullable()->after('referer_url');
            $table->string('user_agent',255)->nullable()->after('referer_agent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_redirects', function (Blueprint $table) {
            $table->boolean('is_success')->default(false)->after('redirect_user_ip');
            $table->dropColumn(['redirect_count', 'referer_url', 'referer_agent', 'user_agent']);
        });
    }
}
