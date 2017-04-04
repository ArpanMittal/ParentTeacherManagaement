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
			$table->string('name',100);
			$table->string('profilePhotoPath',100);
			$table->string('gender', 1);
			$table->string('address',100);
			$table->integer('contact');
		});
	}

	public function down()
	{
		Schema::drop('userDetails');
	}
}