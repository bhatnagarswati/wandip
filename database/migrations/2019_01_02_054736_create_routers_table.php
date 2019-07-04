<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('servicerId');
            $table->integer('driverId');
            $table->dateTime('deliveryDate');
            $table->string('departureTime');
            $table->string('timeNote');
            $table->string('arrivalTime');
            $table->string('volumeContained');
            $table->string('price');
            $table->string('priceUnit');
            $table->string('notifyUsers');
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('routers');
    }
}
