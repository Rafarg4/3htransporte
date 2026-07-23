<?php

namespace Database\Factories;

use App\Models\Camion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CamionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Camion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'marca' => $this->faker->text,
        'modelo' => $this->faker->text,
        'nro_chasis' => $this->faker->text,
        'id_chofer' => $this->faker->text,
        'chapa' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
