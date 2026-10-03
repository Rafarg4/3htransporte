<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoMonedaToViaticosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Los viaticos existentes quedan en Guaranies (PYG).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->string('tipo_moneda')->default('PYG')->after('monto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->dropColumn('tipo_moneda');
        });
    }
}
