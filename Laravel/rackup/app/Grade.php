<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model {

	protected $table = 'grades';
	public $timestamps = true;

	public function getStudent()
	{
		return $this->hasMany('App\Student', 'grade_id');
	}

	public function getTeacher()
	{
		return $this->belongsToMany('App\User');
	}

}