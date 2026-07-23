<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('id');
            $table->text('fecha');
            $table->text('establecimineto');
            $table->text('punto');
            $table->text('numero');
            $table->text('tipodocumento');
            $table->text('condifcionpago');
            $table->text('moneda');
            $table->text('receiptid');
            $table->text('descripcion');
            $table->text('tipoemision');
            $table->text('tipotransaccion');
            $table->text('cliente');
            $table->text('ruc');
            $table->text('nombre');
            $table->text('cpais');
            $table->text('tipopago');
            $table->text('monto');
            $table->text('totalpago');
            $table->text('totalredondeo');
            $table->text('codigoseguridadaleatorio');
            $table->text('items');
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
        Schema::drop('facturas');
    }
}
