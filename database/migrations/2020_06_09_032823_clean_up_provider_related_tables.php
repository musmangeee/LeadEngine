<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanUpProviderRelatedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('provider_delivery_schedules');
        Schema::dropIfExists('provider_channels');
        Schema::dropIfExists('provider_channel_delivery_schedules');
        Schema::dropIfExists('panda_channels');
        Schema::dropIfExists('panda_channel_delivery_schedules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('provider_delivery_schedules')) {
            Schema::create('provider_delivery_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('provider_id');
                $table->string('day_of_week');
                $table->integer('daily_cap');
                $table->time('start_at')->nullable();
                $table->time('end_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('provider_channels')) {
            Schema::create('provider_channels', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('channel_id');
                $table->string('name');
                $table->string('status');
                $table->unsignedBigInteger('provider_id');
                $table->string('channel_key');
                $table->decimal('price',15,2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('provider_channel_delivery_schedules')) {
            Schema::create('provider_channel_delivery_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('provider_channel_id');
                $table->string('day_of_week');
                $table->integer('daily_cap');
                $table->time('start_at')->nullable();
                $table->time('end_at')->nullable();
                $table->timestamps();
            });
           
        }

        if (!Schema::hasTable('panda_channels')) {
            Schema::create('panda_channels', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('provider_id');
                $table->string('channel_key');
                $table->string('channels');
                $table->string('tier');
                $table->string('portfolio');
                $table->string('api_key');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('panda_channel_delivery_schedules')) {
            Schema::create('panda_channel_delivery_schedules', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('panda_channel_id');
                $table->string('day_of_week');
                $table->integer('daily_cap');
                $table->time('start_at')->nullable();
                $table->time('end_at')->nullable();
                $table->timestamps();
            });
        }
    }
}
