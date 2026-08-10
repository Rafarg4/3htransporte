<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEstacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estaciones', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Estaciones que antes venian fijas en el select de Vale Combustible.
        DB::table('estaciones')->insert([
            ['nombre' => 'Ecop', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Petrobras', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estaciones');
    }
}
