<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoAnhoColorEjesToCamionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camions', function (Blueprint $table) {
            $table->text('tipo')->nullable()->after('modelo');
            $table->text('anho')->nullable()->after('tipo');
            $table->text('color')->nullable()->after('anho');
            $table->text('ejes')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camions', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'anho', 'color', 'ejes']);
        });
    }
}
