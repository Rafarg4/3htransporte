<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLiquidadoToViaticosValeCombustiblesOrdenCargasTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->text('liquidado')->nullable()->after('id_liquidacion');
        });

        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->text('liquidado')->nullable()->after('id_liquidacion');
        });

        Schema::table('orden_cargas', function (Blueprint $table) {
            $table->text('liquidado')->nullable()->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viaticos', function (Blueprint $table) {
            $table->dropColumn('liquidado');
        });

        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->dropColumn('liquidado');
        });

        Schema::table('orden_cargas', function (Blueprint $table) {
            $table->dropColumn('liquidado');
        });
    }
}
