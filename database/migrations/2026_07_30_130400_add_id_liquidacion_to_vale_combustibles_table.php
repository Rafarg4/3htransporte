<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdLiquidacionToValeCombustiblesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->text('id_liquidacion')->nullable()->after('realizado_por');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vale_combustibles', function (Blueprint $table) {
            $table->dropColumn('id_liquidacion');
        });
    }
}
