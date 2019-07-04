<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
			$table->string('title');
			$table->text('shortDescription');
            $table->text('fullDescription');
			$table->string('author');
			$table->string('blogImage');
			$table->dateTime('addedOn');
			$table->string('metaTitle');
			$table->string('metaDescription');
			$table->string('metaKeywords');
			$table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('blogs');
    }
}
