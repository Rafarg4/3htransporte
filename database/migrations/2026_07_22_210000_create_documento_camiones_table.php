<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoCamionesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_camiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_camion');
            $table->string('nombre_archivo');
            $table->timestamps();

            $table->foreign('id_camion')->references('id')->on('camions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documento_camiones');
    }
}
