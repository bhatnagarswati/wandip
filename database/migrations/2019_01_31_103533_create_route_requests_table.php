<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('routeId');
            $table->integer('customerId');
            $table->integer('driverId');
            $table->integer('servicerId');
			$table->text('requestedAddress');
			$table->integer('requestedRoute');
            $table->string('requestedQty');
            $table->string('requestedMassUnit');
            $table->string('requestedUnitPrice');
			$table->double('customerLat')->default(NULL);
			$table->double('customerLong')->default(NULL);
			$table->dateTime('requestedDate');
			$table->string('estimatedCalPrice')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('markedStatus');
			$table->string('languageType')->default('en');
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
        Schema::dropIfExists('route_requests');
    }
}
