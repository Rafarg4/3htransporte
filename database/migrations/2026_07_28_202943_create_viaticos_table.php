<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViaticosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viaticos', function (Blueprint $table) {
            $table->id('id');
            $table->text('numero');
            $table->text('fecha');
            $table->text('id_chofer');
            $table->text('numero_remision');
            $table->text('descripcion');
            $table->text('id_orden_carga');
            $table->text('cargado_por');
            $table->text('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('viaticos');
    }
}
