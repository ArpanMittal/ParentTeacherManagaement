<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

	protected $table = 'users';
	public $timestamps = true;

	public function userRole()
	{
		return $this->belongsTo('Role', 'role_id');
	}

	public function getUserDetails()
	{
		return $this->hasOne('App\UserDetails', 'user_id');
	}

	public function getStudent()
	{
		return $this->belongsToMany('App\Student');
	}

	public function getClass()
	{
		return $this->belongsToMany('App\Grade');
	}

	public function getTeacherSlot()
	{
		return $this->hasMany('TeacherAppointmentSlots', 'teacher_id');
	}

	public function getAppointment()
	{
		return $this->hasMany('AppointmentRequest', 'parent_id');
	}

}