<?php

namespace Database\Factories;

use App\Models\Factura;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacturaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Factura::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fecha' => $this->faker->text,
        'establecimineto' => $this->faker->text,
        'punto' => $this->faker->text,
        'numero' => $this->faker->text,
        'tipodocumento' => $this->faker->text,
        'condifcionpago' => $this->faker->text,
        'moneda' => $this->faker->text,
        'receiptid' => $this->faker->text,
        'descripcion' => $this->faker->text,
        'tipoemision' => $this->faker->text,
        'tipotransaccion' => $this->faker->text,
        'cliente' => $this->faker->text,
        'ruc' => $this->faker->text,
        'nombre' => $this->faker->text,
        'cpais' => $this->faker->text,
        'tipopago' => $this->faker->text,
        'monto' => $this->faker->text,
        'totalpago' => $this->faker->text,
        'totalredondeo' => $this->faker->text,
        'codigoseguridadaleatorio' => $this->faker->text,
        'items' => $this->faker->text,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
