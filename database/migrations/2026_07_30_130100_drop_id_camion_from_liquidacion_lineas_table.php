<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropIdCamionFromLiquidacionLineasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacion_fletes', function (Blueprint $table) {
            $table->dropColumn('id_camion');
        });

        Schema::table('liquidacion_descuentos', function (Blueprint $table) {
            $table->dropColumn('id_camion');
        });

        Schema::table('liquidacion_gastos_administrativos', function (Blueprint $table) {
            $table->dropColumn('id_camion');
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
            $table->text('id_camion')->nullable();
        });

        Schema::table('liquidacion_descuentos', function (Blueprint $table) {
            $table->text('id_camion')->nullable();
        });

        Schema::table('liquidacion_gastos_administrativos', function (Blueprint $table) {
            $table->text('id_camion')->nullable();
        });
    }
}
