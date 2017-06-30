<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsClassTeacherToGradeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_user', function (Blueprint $table) {
            $table->boolean('is_class_teacher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_user', function (Blueprint $table) {
            $table->dropColumn('is_class_teacher');
        });
    }
}
