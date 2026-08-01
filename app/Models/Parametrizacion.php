<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Parametrizacion
 * @package App\Models
 *
 * @property string $recargo_tolerancia
 * @property string $recargo_precio
 */
class Parametrizacion extends Model
{
    public $table = 'parametrizaciones';

    public $fillable = [
        'recargo_tolerancia',
        'recargo_precio',
    ];

    protected $casts = [
        'recargo_tolerancia' => 'string',
        'recargo_precio' => 'string',
    ];

    /**
     * Devuelve el único registro de parametrización, creándolo con los
     * valores por defecto si todavía no existe.
     *
     * @return Parametrizacion
     */
    public static function actual()
    {
        return static::first() ?? static::create([]);
    }
}
