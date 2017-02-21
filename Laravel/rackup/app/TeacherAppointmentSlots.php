<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherAppointmentSlots extends Model {

	protected $table = 'teacherAppointmentsSlots';
	public $timestamps = true;

	public function getAppointment()
	{
		return $this->hasOne('AppointmentRequest', 'teacherAppointmentSlot_id');
	}

}