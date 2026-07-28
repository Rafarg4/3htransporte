<?php

namespace Database\Factories;

use App\Models\OrdenCarga;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrdenCargaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrdenCarga::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_proveedor' => $this->faker->text,
        'id_producto' => $this->faker->text,
        'origen' => $this->faker->text,
        'destino' => $this->faker->text,
        'id_camion' => $this->faker->text,
        'carreta' => $this->faker->text,
        'estado' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
