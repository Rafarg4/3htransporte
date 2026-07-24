<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValeCombustiblesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vale_combustibles', function (Blueprint $table) {
            $table->id('id');
            $table->text('numero_vale');
            $table->text('vigencia_desde');
            $table->text('vigencia_hasta');
            $table->text('id_camion');
            $table->text('nombre_estacion');
            $table->text('codigo');
            $table->text('direccion');
            $table->text('producto');
            $table->text('importe');
            $table->text('litros');
            $table->text('realizado_por');
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
        Schema::drop('vale_combustibles');
    }
}
