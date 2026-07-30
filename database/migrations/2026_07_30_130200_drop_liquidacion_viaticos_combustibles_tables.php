<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropLiquidacionViaticosCombustiblesTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('liquidacion_viaticos');
        Schema::dropIfExists('liquidacion_combustibles');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Datos intencionalmente no recuperables: se reemplazan por la
        // seleccion de registros reales de Viatico/ValeCombustible.
    }
}
