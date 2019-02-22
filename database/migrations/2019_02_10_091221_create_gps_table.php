<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('daily_stock_purchase')->default('0');//input
            $table->integer('intake')->default('0');//input
            $table->integer('budget_estimate')->default('0');//calc
            $table->integer('GP(£)')->default('0');
            $table->integer('GP(%)')->default('0');
            $table->text('day');
            $table->dateTime('date');
            $table->integer('week_id')->default('0');
            $table->integer('unique_week_id')->default('0');
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
        Schema::dropIfExists('gps');
    }
}
