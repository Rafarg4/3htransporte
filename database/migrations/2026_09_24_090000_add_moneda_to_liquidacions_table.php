<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonedaToLiquidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * moneda/monto_moneda quedan en null para Guaranies. Para otra moneda se guarda la
     * cotizacion del momento, asi el PDF no cambia si despues se actualiza Monedas.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacions', function (Blueprint $table) {
            $table->string('moneda')->nullable()->after('pagado');
            $table->decimal('monto_moneda', 15, 4)->nullable()->after('moneda');
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
            $table->dropColumn(['moneda', 'monto_moneda']);
        });
    }
}
