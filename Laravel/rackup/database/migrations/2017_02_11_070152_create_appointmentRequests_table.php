<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentRequestsTable extends Migration {

	public function up()
	{
		Schema::create('appointmentRequests', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('parent_id')->unsigned();
			$table->integer('teacher_id')->unsigned();
			$table->boolean('isApproved');
			$table->boolean('isCancel')->default(false);
			$table->integer('teacherAppointmentsSlot_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('appointmentRequests');
	}
}