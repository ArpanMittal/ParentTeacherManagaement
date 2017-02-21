<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentsTable extends Migration {

	public function up()
	{
		Schema::create('students', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name',50);
			$table->integer('age');
			$table->integer('grade_id')->unsigned();
			$table->integer('parent_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('students');
	}
}