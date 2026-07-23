<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenCargasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_cargas', function (Blueprint $table) {
            $table->id('id');
            $table->text('id_proveedor');
            $table->text('id_producto');
            $table->text('origen');
            $table->text('destino');
            $table->text('id_camion');
            $table->text('estado');
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
        Schema::drop('orden_cargas');
    }
}
