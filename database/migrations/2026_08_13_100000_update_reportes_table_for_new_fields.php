<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReportesTableForNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn(['precio_real_flete', 'precio_fletero']);
        });

        Schema::table('reportes', function (Blueprint $table) {
            $table->text('nro_remision')->nullable()->after('id');
            $table->text('fecha')->nullable()->after('nro_remision');
            $table->text('id_cliente')->nullable()->after('fecha');
            $table->text('id_camion')->nullable()->after('id_cliente');
            $table->text('id_chofer')->nullable()->after('id_camion');
            $table->text('id_producto')->nullable()->after('id_chofer');
            $table->text('tramo')->nullable()->after('id_producto');
            $table->text('precio')->nullable()->after('kg_llegada');
            $table->text('monto')->nullable()->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportes', function (Blueprint $table) {
            $table->dropColumn([
                'nro_remision',
                'fecha',
                'id_cliente',
                'id_camion',
                'id_chofer',
                'id_producto',
                'tramo',
                'precio',
                'monto',
            ]);
        });

        Schema::table('reportes', function (Blueprint $table) {
            $table->text('precio_real_flete')->nullable();
            $table->text('precio_fletero')->nullable();
        });
    }
}
