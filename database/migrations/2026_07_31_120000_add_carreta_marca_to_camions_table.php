<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCarretaMarcaToCamionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camions', function (Blueprint $table) {
            $table->text('carreta_marca')->nullable()->after('carreta_chapa');
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
            $table->dropColumn('carreta_marca');
        });
    }
}
