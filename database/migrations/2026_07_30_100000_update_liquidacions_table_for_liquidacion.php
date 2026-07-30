<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLiquidacionsTableForLiquidacion extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liquidacions', function (Blueprint $table) {
            $table->dropColumn('id_propietario');
            $table->text('id_cliente')->after('id');
            $table->text('fecha')->after('id_cliente');
            $table->text('estado')->after('fecha');
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
            $table->dropColumn(['id_cliente', 'fecha', 'estado']);
            $table->text('id_propietario');
        });
    }
}
