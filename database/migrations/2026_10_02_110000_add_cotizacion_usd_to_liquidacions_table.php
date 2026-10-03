<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCotizacionUsdToLiquidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Cotizacion del dolar al momento de liquidar: con ella se pasan a guaranies los viaticos y
     * vales de combustible cargados en USD, sin que un cambio posterior en Monedas los altere.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacions', function (Blueprint $table) {
            $table->decimal('cotizacion_usd', 15, 4)->nullable()->after('monto_moneda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquidacions', function (Blueprint $table) {
            $table->dropColumn('cotizacion_usd');
        });
    }
}
