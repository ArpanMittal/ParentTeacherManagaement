<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContentGradeTable extends Migration {

	public function up()
	{
		Schema::create('content_grade', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('grade_id')->unsigned();
			$table->integer('content_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('content_grade');
	}
}