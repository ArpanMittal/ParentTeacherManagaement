<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeleteContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_delete', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name',50);
            $table->string('url',100);
            $table->integer('contentGradeId')->unsigned();
            $table->string('teacherName');
            $table->string('description');
            $table->integer('type');
            

        });

        Schema::table('categories_delete', function(Blueprint $table) {
            $table->dropColumn('created_at');
            
        });

        Schema::table('categories_delete', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
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
        Schema::drop('categories_delete');
    }
}
