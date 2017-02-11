<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStudentUserTable extends Migration {

	public function up()
	{
		Schema::create('student_user', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('student_id')->unsigned();
			$table->timestamp('timesptamps');
		});
	}

	public function down()
	{
		Schema::drop('student_user');
	}
}