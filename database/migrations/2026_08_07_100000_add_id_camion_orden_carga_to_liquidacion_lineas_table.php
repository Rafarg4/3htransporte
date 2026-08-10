<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCamionOrdenCargaToLiquidacionLineasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacion_fletes', function (Blueprint $table) {
            $table->text('id_camion')->nullable()->after('id_liquidacion');
            $table->text('id_orden_carga')->nullable()->after('id_camion');
        });

        Schema::table('liquidacion_descuentos', function (Blueprint $table) {
            $table->text('id_camion')->nullable()->after('id_liquidacion');
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
            $table->dropColumn(['id_camion', 'id_orden_carga']);
        });

        Schema::table('liquidacion_descuentos', function (Blueprint $table) {
            $table->dropColumn('id_camion');
        });
    }
}
