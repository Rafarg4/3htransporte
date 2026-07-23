<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentoChoferTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documento_chofer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_chofer');
            $table->string('nombre_archivo');
            $table->timestamps();

            $table->foreign('id_chofer')->references('id')->on('chofers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documento_chofer');
    }
}
