<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimestampsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('content_grade', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('content_types', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('grades', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('grade_school', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('grade_user', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('userDetails', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('updated_at');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('content_grade', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('content_types', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('grade_school', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('grade_user', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('userDetails', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
}
