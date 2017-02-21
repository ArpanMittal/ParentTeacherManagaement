<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserDetailsTable extends Migration {

	public function up()
	{
		Schema::create('userDetails', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->string('name',50);
			$table->string('profilePhotoPath',50);
			$table->string('gender', 1);
			$table->string('address',50);
		});
	}

	public function down()
	{
		Schema::drop('userDetails');
	}
}