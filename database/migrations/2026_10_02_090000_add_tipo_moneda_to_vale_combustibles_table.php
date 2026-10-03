<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoMonedaToValeCombustiblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Los vales existentes quedan en Guaranies (PYG).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->string('tipo_moneda')->default('PYG')->after('producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->dropColumn('tipo_moneda');
        });
    }
}
