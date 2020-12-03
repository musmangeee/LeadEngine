<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('affid')->nullable();
            $table->string('affsubid')->nullable();
            $table->string('affsubid2')->nullable();
            $table->string('affsubid3')->nullable();
            $table->string('affsubid4')->nullable();
            $table->string('affsubid5')->nullable();
            $table->decimal('requested_amount', 15, 2);
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('rent_or_own');
            $table->integer('address_months');
            $table->integer('address_years');
            $table->string('dl_state');
            $table->string('dl_number');
            $table->string('ssn');
            $table->string('is_military');
            $table->string('income_type');
            $table->string('pay_frequency');
            $table->date('next_pay_date');
            $table->integer('net_monthly_income');
            $table->string('emp_name');
            $table->string('emp_phone');
            $table->string('job_title');
            $table->string('emp_months');
            $table->string('emp_years');
            $table->string('pay_type');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('routing_number');
            $table->string('tcpa_optin')->nullable();
            $table->string('ref_url')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('mobile_device');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
