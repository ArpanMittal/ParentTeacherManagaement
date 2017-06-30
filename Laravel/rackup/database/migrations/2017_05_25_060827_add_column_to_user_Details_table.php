<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('userDetails', function (Blueprint $table) {
            $table->dropColumn('contact');
            $table->dropColumn('gender');
        });
        Schema::table('userDetails', function (Blueprint $table) {
            $table->string('contact');
            $table->string('fatherName');
            $table->string('motherName');
            $table->string('secondaryContact');
        });
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->dropColumn('contactNo');
            $table->dropColumn('parentContact');
        });
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->string('contactNo');
            $table->string('parentContact');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userDetails', function (Blueprint $table) {
            $table->dropColumn('contact');
            $table->integer('contact',10);
        });
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->bigInteger('contactNo');
            $table->bigInteger('parentContact');
            $table->dropColumn('contactNo');
            $table->dropColumn('parentContact');
        });
    }
}
