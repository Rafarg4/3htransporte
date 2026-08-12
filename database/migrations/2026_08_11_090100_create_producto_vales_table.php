<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductoValesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_vales', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Productos que antes venian fijos en el select de Vale Combustible.
        DB::table('producto_vales')->insert([
            ['nombre' => 'Diesel S50', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Nafta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('producto_vales');
    }
}
