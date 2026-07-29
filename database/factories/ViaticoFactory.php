<?php

namespace Database\Factories;

use App\Models\Viatico;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViaticoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Viatico::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'numero' => $this->faker->text,
        'fecha' => $this->faker->text,
        'id_chofer' => $this->faker->text,
        'numero_remision' => $this->faker->text,
        'descripcion' => $this->faker->text,
        'id_orden_carga' => $this->faker->text,
        'cargado_por' => $this->faker->text,
        'estado' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
