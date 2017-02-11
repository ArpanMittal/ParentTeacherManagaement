<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTeacherAppointmentsSlotsTable extends Migration {

	public function up()
	{
		Schema::create('teacherAppointmentsSlots', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('teacher_id')->unsigned();
			$table->enum('duration', array('15', '30', '45', '60'));
			$table->enum('day', array('Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday'));
			$table->time('startTime');
			$table->boolean('isBooked')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('teacherAppointmentsSlots');
	}
}