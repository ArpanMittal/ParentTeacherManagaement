<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGradeSchoolTable extends Migration {

	public function up()
	{
		Schema::create('grade_school', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('grad_id')->unsigned();
			$table->integer('school_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('grade_school');
	}
}