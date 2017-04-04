<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolsTable extends Migration {

	public function up()
	{
		Schema::create('schools', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name',100);
			$table->string('city',50);
			$table->string('board',50);
		});
	}

	public function down()
	{
		Schema::drop('schools');
	}
}