<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentImageDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //


        Schema::create('image_details', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('circle_time');
            $table->string('1st meal');
            $table->string('2nd meal');
            $table->string('3rd meal');
            $table->string('activities');
            $table->string('evening activities');
            $table->string('others');
            $table->integer('image_student_id')->unsigned();
        });

//        Schema::table('image_details',function(Blueprint $table){
//            $table->foreign('image_student_id')->references('id')->on('image_students')->onDelete('cascade')
//                ->onUpdate('cascade');
//        });

        Schema::table('image_students', function(Blueprint $table) {
            $table->boolean('is_broadcast')->default(true);
        });

        Schema::table('image_details', function(Blueprint $table) {
            $table->dropColumn('created_at');

        });

        Schema::table('image_details', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->integer('school_id')->unsigned()->default(1);


        });

        Schema::table('categories',function(Blueprint $table){
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade')
                ->onUpdate('cascade');
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

        Schema::drop('image_details');
    }
}
