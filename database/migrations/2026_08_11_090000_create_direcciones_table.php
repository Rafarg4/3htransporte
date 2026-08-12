<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        // Direcciones que antes venian fijas en el select de Vale Combustible.
        $nombres = [
            'Vacay',
            'María Auxiliadora',
            'Yatytay',
            'Edelira 60',
            'Obligado',
            'Santa Rita',
            'Santa Inés',
            'Capitán Meza',
            'Capitán Miranda',
        ];

        foreach ($nombres as $nombre) {
            DB::table('direcciones')->insert([
                'nombre' => $nombre,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('direcciones');
    }
}
