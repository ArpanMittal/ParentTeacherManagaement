<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSchoolGradeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::table('grade_school')->insert([['grad_id'=>'2','school_id'=> '1'],['grad_id'=>'3','school_id'=> '1'],['grad_id'=>'4','school_id'=> '1'],['grad_id'=>'6','school_id'=> '1'],['grad_id'=>'7','school_id'=> '1']]);
        DB::table('grade_school')->insert([['grad_id'=>'2','school_id'=> '2'],['grad_id'=>'3','school_id'=> '2'],['grad_id'=>'4','school_id'=> '2'],['grad_id'=>'6','school_id'=> '2'],['grad_id'=>'7','school_id'=> '2']]);

//        Schema::table('users', function(Blueprint $table)
//        {
//            $table->dropUnique('password');
//
//        });
//
//        Schema::table('grades', function(Blueprint $table)
//        {
//            $table->dropUnique('room_number');
//
//        });

        Schema::table('calendar_events', function(Blueprint $table) {
            $table->integer('school_id')->unsigned()->default(1);


        });

        Schema::table('calendar_events',function(Blueprint $table){
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')
                ->onUpdate('cascade');
        });

        //initially all students belong to gurgaon center
        Schema::table('content_grade', function(Blueprint $table) {
            $table->integer('grade_school_id')->unsigned()->default(1);


        });

        Schema::table('content_grade',function(Blueprint $table){
            $table->foreign('grade_school_id')->references('id')->on('schools')->onDelete('cascade')
                ->onUpdate('cascade')->default();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropForeign('school_id');
        });

        Schema::table('content_grade', function (Blueprint $table) {
            $table->dropForeign('grade_school_id');
        });
    }
}
