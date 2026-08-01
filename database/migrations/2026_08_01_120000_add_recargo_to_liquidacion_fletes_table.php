<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecargoToLiquidacionFletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacion_fletes', function (Blueprint $table) {
            $table->text('diferencia')->nullable()->after('kg_destino');
            $table->text('recargo_tolerancia')->nullable()->after('valor');
            $table->text('recargo_precio')->nullable()->after('recargo_tolerancia');
            $table->text('recargo')->nullable()->after('recargo_precio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liquidacion_fletes', function (Blueprint $table) {
            $table->dropColumn(['diferencia', 'recargo_tolerancia', 'recargo_precio', 'recargo']);
        });
    }
}
