<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

	protected $table = 'roles';
	public $timestamps = true;

	public function userRole()
	{
		return $this->hasMany('User', 'id');
	}

}