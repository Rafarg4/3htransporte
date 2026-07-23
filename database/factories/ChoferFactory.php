<?php

namespace Database\Factories;

use App\Models\Chofer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chofer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'documento' => $this->faker->text,
        'nombre' => $this->faker->text,
        'documento' => $this->faker->text,
        'estado' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
