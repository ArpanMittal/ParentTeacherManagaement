<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnClassTeacherFromGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign('grades_classTeacher_foreign');
        });
        Schema::table('grades', function (Blueprint $table) {
            $table->dropColumn('classTeacher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->integer('classTeacher')->unsigned();
        });
        Schema::table('grades', function (Blueprint $table) {
            $table->foreign('classTeacher')->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
}
