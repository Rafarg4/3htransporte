<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCamionChoferOrdenCargaToLiquidacionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacions', function (Blueprint $table) {
            $table->text('id_camion')->nullable()->after('id_cliente');
            $table->text('id_chofer')->nullable()->after('id_camion');
            $table->text('id_orden_carga')->nullable()->after('id_chofer');
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
            $table->dropColumn(['id_camion', 'id_chofer', 'id_orden_carga']);
        });
    }
}
