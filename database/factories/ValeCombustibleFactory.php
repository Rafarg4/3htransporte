<?php

namespace Database\Factories;

use App\Models\ValeCombustible;
use Illuminate\Database\Eloquent\Factories\Factory;

class ValeCombustibleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ValeCombustible::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'numero_vale' => $this->faker->text,
        'vigencia_desde' => $this->faker->text,
        'vigencia_hasta' => $this->faker->text,
        'id_camion' => $this->faker->text,
        'nombre_estacion' => $this->faker->text,
        'codigo' => $this->faker->text,
        'direccion' => $this->faker->text,
        'producto' => $this->faker->text,
        'importe' => $this->faker->text,
        'litros' => $this->faker->text,
        'realizado_por' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
