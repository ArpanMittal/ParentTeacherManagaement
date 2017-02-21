<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->foreign('role_id')->references('id')->on('roles')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('userDetails', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('students', function(Blueprint $table) {
			$table->foreign('grade_id')->references('id')->on('grades')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('students', function(Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('student_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('student_user', function(Blueprint $table) {
			$table->foreign('student_id')->references('id')->on('students')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('grade_user', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('grade_user', function(Blueprint $table) {
			$table->foreign('grade_id')->references('id')->on('grades')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->foreign('teacher_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->foreign('teacherAppointmentsSlot_id')->references('id')->on('teacherAppointmentsSlots')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
			$table->foreign('teacher_id')->references('id')->on('users')
						->onDelete('cascade')
						->onUpdate('cascade');
		});
		Schema::table('content_grade', function(Blueprint $table) {
			$table->foreign('grade_id')->references('id')->on('grades')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('content_grade', function(Blueprint $table) {
			$table->foreign('content_id')->references('id')->on('contents')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('grade_school', function(Blueprint $table) {
			$table->foreign('grad_id')->references('id')->on('grades')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('grade_school', function(Blueprint $table) {
			$table->foreign('school_id')->references('id')->on('schools')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			$table->dropForeign('users_role_id_foreign');
		});
		Schema::table('userDetails', function(Blueprint $table) {
			$table->dropForeign('userDetails_user_id_foreign');
		});
		Schema::table('students', function(Blueprint $table) {
			$table->dropForeign('students_grade_id_foreign');
		});
		Schema::table('students', function(Blueprint $table) {
			$table->dropForeign('students_parent_id_foreign');
		});
		Schema::table('student_user', function(Blueprint $table) {
			$table->dropForeign('student_user_user_id_foreign');
		});
		Schema::table('student_user', function(Blueprint $table) {
			$table->dropForeign('student_user_student_id_foreign');
		});
		Schema::table('grade_user', function(Blueprint $table) {
			$table->dropForeign('grade_user_user_id_foreign');
		});
		Schema::table('grade_user', function(Blueprint $table) {
			$table->dropForeign('grade_user_grade_id_foreign');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->dropForeign('appointmentRequests_parent_id_foreign');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->dropForeign('appointmentRequests_teacher_id_foreign');
		});
		Schema::table('appointmentRequests', function(Blueprint $table) {
			$table->dropForeign('appointmentRequests_teacherAppointmentsSlot_id_foreign');
		});
		Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
			$table->dropForeign('teacherAppointmentsSlots_teacher_id_foreign');
		});
		Schema::table('content_grade', function(Blueprint $table) {
			$table->dropForeign('content_grade_grade_id_foreign');
		});
		Schema::table('content_grade', function(Blueprint $table) {
			$table->dropForeign('content_grade_content_id_foreign');
		});
		Schema::table('grade_school', function(Blueprint $table) {
			$table->dropForeign('grade_school_grad_id_foreign');
		});
		Schema::table('grade_school', function(Blueprint $table) {
			$table->dropForeign('grade_school_school_id_foreign');
		});
	}
}