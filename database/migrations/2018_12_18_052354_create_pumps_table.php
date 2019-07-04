<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps', function (Blueprint $table) {
			
			$table->increments('pumpId');
			$table->integer('servicerId');
			$table->string('pumpTitle');
			$table->text('pumpDescription');
            $table->string('pumpAddress');
            $table->string('pumpPrice');
            $table->string('pumpPic');
            $table->integer('status')->default(0);
			$table->softDeletes();
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
        Schema::dropIfExists('pumps');
    }
}
