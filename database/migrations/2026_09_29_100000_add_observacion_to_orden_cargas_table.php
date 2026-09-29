<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservacionToOrdenCargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orden_cargas', function (Blueprint $table) {
            $table->text('observacion')->nullable()->after('id_camion');
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
            $table->dropColumn('observacion');
        });
    }
}
