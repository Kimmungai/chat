<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('order_name');
            $table->string('pick_up_date');
            $table->string('pick_up_time');
            $table->string('pick_up_address');
            $table->string('drop_off_date');
            $table->string('drop_off_time');
            $table->string('drop_off_address');
            $table->integer('num_of_cars')->unsigned();
            $table->integer('number_of_people')->unsigned();
            $table->string('luggage_num');
            $table->string('car_type');
            $table->longText('remarks');
            $table->integer('bid_id')->unsigned()->index();
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
        Schema::dropIfExists('orders');
    }
}
