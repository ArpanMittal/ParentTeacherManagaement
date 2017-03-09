<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolesTable extends Migration {

	public function up()
	{
		Schema::create('roles', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('role_name',25)->unique();
		});

		DB::table('roles')->insert([
			['role_name' => 'admin'],
            ['role_name' => 'parent'],
			['role_name' => 'principal'],
			['role_name' => 'teacher']
		]);
	}

	public function down()
	{
		Schema::drop('roles');
	}
}