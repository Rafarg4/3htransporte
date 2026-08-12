<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Direccion
 * @package App\Models
 *
 * @property string $nombre
 */
class Direccion extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'direcciones';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nombre'
    ];

    protected $casts = [
        'nombre' => 'string'
    ];

    public static $rules = [
        'nombre' => 'required|string|max:255|unique:direcciones,nombre'
    ];
}
