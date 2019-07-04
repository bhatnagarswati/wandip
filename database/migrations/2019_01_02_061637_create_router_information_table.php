<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouterInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('router_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('routerId');
            $table->string('location');
            $table->decimal('locationLat', 10, 3);
            $table->decimal('locationLong', 10, 3);
            $table->integer('sortOrder');
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
        Schema::dropIfExists('router_informations');
    }
}
