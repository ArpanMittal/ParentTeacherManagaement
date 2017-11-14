<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MapUserSchool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('schools')->insert([['name'=>'Rackup Campbridge','city'=> 'Gurgaon','board'=>'CBSE'],['name'=>'Rackup Campbridge','city'=> 'Kota','coard'=>'CBSE']]);
        //
        //initially all students belong to gurgaon center
        Schema::table('users', function(Blueprint $table) {
            $table->integer('school_id')->unsigned()->default(1);


        });

        Schema::table('users',function(Blueprint $table){
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('school_id');
        });
    }
}
