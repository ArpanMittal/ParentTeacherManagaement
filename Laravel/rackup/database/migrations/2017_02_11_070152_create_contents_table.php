<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentsTable extends Migration {

	public function up()
	{
		Schema::create('contents', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name',50);
		});
	}

	public function down()
	{
		Schema::drop('contents');
	}
}