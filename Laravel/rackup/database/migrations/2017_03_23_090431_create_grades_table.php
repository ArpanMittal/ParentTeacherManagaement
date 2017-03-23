<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGradesTable extends Migration {

	public function up()
	{
		Schema::create('grades', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('grade_name',50)->unique();
			$table->integer('room_number')->unique();
		});
	}

	public function down()
	{
		Schema::drop('grades');
	}
}