<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsFromTeacherAppointmentsSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->dropColumn('duration');
            $table->dropColumn('day');
            $table->dropColumn('startTime');
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->enum('duration', array('15', '30', '45', '60'));
            $table->enum('day', array('Monday', 'Tuesday', 'Wednesday', 'Thrusday', 'Friday'));
            $table->time('startTime');
            $table->date('date');
        });
    }
}
