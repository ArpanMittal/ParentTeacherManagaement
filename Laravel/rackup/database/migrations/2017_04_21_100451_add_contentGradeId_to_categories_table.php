<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContentGradeIdToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            
            $table->integer('contentGradeId')->unsigned();
            $table->string('teacherName',100);
           
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->foreign('contentGradeId')->references('id')->on('content_grade')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
        
        Schema::table('categories', function(Blueprint $table) {
            $table->dropForeign('categories_content_id_foreign');
            $table->dropColumn('content_id');
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign('contentGradeId');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('contentGradeId');
            $table->dropColumn('teacherName');
        });
    }
}
