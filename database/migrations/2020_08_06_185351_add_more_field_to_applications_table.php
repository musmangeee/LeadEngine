<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('ssn');
            $table->date('hire_date')->nullable()->after('emp_phone');
            $table->string('emp_type')->nullable()->after('hire_date');
            $table->date('last_pay_date')->nullable()->after('next_pay_date');
            $table->date('second_pay_date')->nullable()->after('last_pay_date');
            $table->string('account_type')->nullable()->after('bank_name');
            $table->integer('bank_months')->nullable()->after('account_number');
            $table->integer('bank_years')->nullable()->after('bank_months');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['dob','hire_date','emp_type','last_pay_date','second_pay_date','account_type','bank_months','bank_years']);
        });
    }
}
