<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsValueToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('appointmentRequests', function(Blueprint $table) {
//            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
//            
//        });
        Schema::table('appointmentRequests', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('calendar_events', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('calendar_events', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('contents', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('contents', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('content_grade', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('content_grade', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('content_types', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('content_types', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('grades', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('grades', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('grade_school', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('grade_school', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('grade_user', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('grade_user', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('image_students', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('image_students', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('roles', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('roles', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('schools', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('schools', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('students', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('students', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
//        Schema::table('student_user', function(Blueprint $table) {
//            $table->dropColumn('timestamps');
////            $table->dropColumn('created_at');
////            $table->dropColumn('updated_at');
//        });
//        Schema::table('student_user', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//        });
        Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('userDetails', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('userDetails', function(Blueprint $table) {
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
        Schema::table('users', function(Blueprint $table){
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointmentRequests', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('appointmentRequests', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('calendar_events', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('calendar_events', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('categories', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('contents', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('contents', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('content_grade', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('content_grade', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('content_types', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('content_types', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('grades', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('grades', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('grade_school', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('grade_school', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('grade_user', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('grade_user', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('image_students', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('image_students', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('roles', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('roles', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('schools', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('schools', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('students', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('students', function(Blueprint $table) {
            $table->timestamps();
        });
//        Schema::table('student_user', function(Blueprint $table) {
//            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
//        });
//        Schema::table('student_user', function(Blueprint $table) {
//            $table->timestamps();
//        });
        Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('teacherAppointmentsSlots', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('userDetails', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('userDetails', function(Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('created_at');
//            $table->dropColumn('updated_at');
        });
        Schema::table('users', function(Blueprint $table) {
            $table->timestamps();
        });

    }
}
