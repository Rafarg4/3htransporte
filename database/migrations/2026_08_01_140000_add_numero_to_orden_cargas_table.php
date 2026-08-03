<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddNumeroToOrdenCargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_cargas', function (Blueprint $table) {
            $table->text('numero')->nullable()->after('id');
        });

        // Backfill: numera las ordenes de carga existentes de forma correlativa
        // segun su orden de creacion, para que el campo nunca quede vacio.
        DB::table('orden_cargas')->orderBy('id')->select('id')
            ->get()
            ->each(function ($fila, $indice) {
                DB::table('orden_cargas')->where('id', $fila->id)->update([
                    'numero' => $indice + 1,
                ]);
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orden_cargas', function (Blueprint $table) {
            $table->dropColumn('numero');
        });
    }
}
