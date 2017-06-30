<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToAppointmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->text('reasonOfAppointment');
            $table->text('cancellationReason');
            $table->bigInteger('contactNo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointmentRequests', function (Blueprint $table) {
            $table->dropColumn('reasonOfAppointment');
            $table->dropColumn('cancellationReason');
            $table->dropColumn('contactNo');
            
        });
    }
}
