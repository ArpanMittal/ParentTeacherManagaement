<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

	protected $table = 'contents';
	public $timestamps = true;

	public function getContent()
	{
		return $this->belongsToMany('Grade', 'grade_id');
	}

	public function getCategory()
	{
		return $this->hasMany('Category', 'category_id');
	}

}