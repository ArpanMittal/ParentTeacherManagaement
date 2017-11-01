<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
//        Schema::create('notifications', function(Blueprint $table) {
//            $table->increments('id');
//            $table->string('message');
//            $table->integer('parent_id')->unsigned();
//            $table->foreign('parent_id')->references('id')->on('users')
//                ->onDelete('restrict')
//                ->onUpdate('restrict');
//
//        });
//
//        Schema::table('notifications', function(Blueprint $table) {
//            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
//        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('notifications');
        Schema::table('notifications', function(Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
