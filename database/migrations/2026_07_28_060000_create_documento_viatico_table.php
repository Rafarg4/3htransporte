<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoViaticoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_viatico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_viatico');
            $table->string('nombre_archivo');
            $table->timestamps();

            $table->foreign('id_viatico')->references('id')->on('viaticos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documento_viatico');
    }
}
