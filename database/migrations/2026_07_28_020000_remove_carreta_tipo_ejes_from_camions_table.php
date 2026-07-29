<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCarretaTipoEjesFromCamionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camions', function (Blueprint $table) {
            $table->dropColumn(['carreta_tipo', 'carreta_ejes']);
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
            $table->text('carreta_tipo')->nullable()->after('chapa');
            $table->text('carreta_ejes')->nullable()->after('carreta_tipo');
        });
    }
}
