<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name',50);
			$table->string('url',100);
			$table->integer('content_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('categories');
	}
}