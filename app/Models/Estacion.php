<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Estacion
 * @package App\Models
 *
 * @property string $nombre
 */
class Estacion extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'estaciones';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nombre'
    ];

    protected $casts = [
        'nombre' => 'string'
    ];

    public static $rules = [
        'nombre' => 'required|string|max:255|unique:estaciones,nombre'
    ];
}
