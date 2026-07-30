<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionGastosAdministrativosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidacion_gastos_administrativos', function (Blueprint $table) {
            $table->id('id');
            $table->text('id_liquidacion');
            $table->text('id_camion');
            $table->text('fecha');
            $table->text('concepto');
            $table->text('valor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('liquidacion_gastos_administrativos');
    }
}
