<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('username',25)->unique();
			$table->string('password',25)->unique();
			$table->boolean('active')->default(true);
			$table->integer('role_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}