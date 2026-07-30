<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LiquidacionCombustible
 * @package App\Models
 *
 * @property string $id_liquidacion
 * @property string $id_camion
 * @property string $fecha
 * @property string $nombre_estacion
 * @property string $litros
 * @property string $precio
 * @property string $valor
 */
class LiquidacionCombustible extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacion_combustibles';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_liquidacion',
        'id_camion',
        'fecha',
        'nombre_estacion',
        'litros',
        'precio',
        'valor'
    ];

    protected $casts = [
        'id_liquidacion' => 'string',
        'id_camion' => 'string',
        'fecha' => 'string',
        'nombre_estacion' => 'string',
        'litros' => 'string',
        'precio' => 'string',
        'valor' => 'string'
    ];

    public static $rules = [
        'id_camion' => 'required',
        'fecha' => 'required',
        'nombre_estacion' => 'required',
        'valor' => 'required'
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'id_liquidacion');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }
}
