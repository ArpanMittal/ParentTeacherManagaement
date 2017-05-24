<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('eventId')->unsigned();
            $table->foreign('eventId')->references('id')->on('calendar_events')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('appointmentState');
            $table->string('triggeredBy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_appointments');
    }
}
