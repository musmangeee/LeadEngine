<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableApplicationStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_statuses', function (Blueprint $table) {
            $table->longText('xml_response_record')->nullable()->change();
            $table->decimal('price_received', 15,2)->nullable()->change();
            $table->boolean('status')->default(0)->change();
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
            $table->longText('xml_response_record')->change();
            $table->decimal('price_received', 15,2)->change();
            DB::raw('ALTER TABLE `application_statuses` MODIFY `status` BOOLEAN default 0');
        });
    }
}
