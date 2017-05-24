<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueToCreatedAtTimestampToAuditAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_appointments', function(Blueprint $table) {
            $table->dropColumn('created_at');
        });
        Schema::table('audit_appointments', function (Blueprint $table) {
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
        Schema::table('audit_appointments', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
        Schema::table('audit_appointments', function (Blueprint $table) {
            $table->timestamp('created_at');
        });
    }
}
