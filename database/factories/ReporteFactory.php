<?php

namespace Database\Factories;

use App\Models\Reporte;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReporteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reporte::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kg_origen' => $this->faker->text,
        'kg_llegada' => $this->faker->text,
        'precio_real_flete' => $this->faker->text,
        'precio_fletero' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
