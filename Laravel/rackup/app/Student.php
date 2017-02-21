<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

	protected $table = 'students';
	public $timestamps = true;

	public function getGrade()
	{
		return $this->belongsTo('Grade', 'grade_id');
	}

}