<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model {

	protected $table = 'schools';
	public $timestamps = true;

	public function schoolGrades()
	{
		return $this->belongsToMany('Grade');
	}

}