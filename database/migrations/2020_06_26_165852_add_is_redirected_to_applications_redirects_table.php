<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRedirectedToApplicationsRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_redirects', function (Blueprint $table) {
            $table->boolean('is_success')->default(false)->after('redirect_user_ip');
            $table->datetime('redirected_at')->nullable()->after('is_success');
            $table->text('failed_reason')->nullable()->after('redirected_at');
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
            $table->dropColumn(['is_success','redirected_at','failed_reason']);
        });
    }
}
