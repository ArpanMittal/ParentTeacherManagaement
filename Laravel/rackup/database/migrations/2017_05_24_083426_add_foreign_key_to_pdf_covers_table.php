<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPdfCoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pdf_covers', function (Blueprint $table) {
            $table->foreign('pdf_id')->references('id')->on('categories')
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
        Schema::table('pdf_covers', function (Blueprint $table) {
            $table->dropForeign('pdf_covers_pdf_id_foreign');
        });
    }
}
