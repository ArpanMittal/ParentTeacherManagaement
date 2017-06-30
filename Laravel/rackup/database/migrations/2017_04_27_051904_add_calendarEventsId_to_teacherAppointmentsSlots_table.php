<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalendarEventsIdToTeacherAppointmentsSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->integer('calendarEventsId')->unsigned();
            $table->foreign('calendarEventsId')->references('id')->on('calendar_events')
                ->onDelete('cascade')
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
        Schema::table('teacherAppointmentsSlots', function (Blueprint $table) {
            $table->dropForeign('teacherAppointmentsslots_calendarEventsId_foreign');
            $table->dropColumn('calendarEventsId');
        });
        
    }
}
