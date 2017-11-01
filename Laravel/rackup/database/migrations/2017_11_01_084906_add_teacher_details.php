<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeacherDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('userdetails', function (Blueprint $table) {
            $table->string('pancard');
            $table->string('adharcard');
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
        Schema::table('userdetails', function (Blueprint $table) {
            $table->dropColumn(['pancard', 'adharcard']);
        }); 
    }
}
