<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ValeCombustible
 * @package App\Models
 * @version July 23, 2026, 11:28 pm UTC
 *
 * @property string $numero_vale
 * @property string $vigencia_desde
 * @property string $vigencia_hasta
 * @property string $id_camion
 * @property string $nombre_estacion
 * @property string $codigo
 * @property string $direccion
 * @property string $producto
 * @property string $importe
 * @property string $litros
 * @property string $realizado_por
 */
class ValeCombustible extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'vale_combustibles';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'numero_vale',
        'vigencia_desde',
        'vigencia_hasta',
        'id_camion',
        'nombre_estacion',
        'codigo',
        'direccion',
        'producto',
        'importe',
        'litros',
        'realizado_por'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'numero_vale' => 'string',
        'vigencia_desde' => 'string',
        'vigencia_hasta' => 'string',
        'id_camion' => 'string',
        'nombre_estacion' => 'string',
        'codigo' => 'string',
        'direccion' => 'string',
        'producto' => 'string',
        'importe' => 'string',
        'litros' => 'string',
        'realizado_por' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // Validado en el HTML5 del formulario (resources/views/vale_combustibles/fields.blade.php).
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }
}
