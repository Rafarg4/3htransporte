<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LiquidacionFlete
 * @package App\Models
 *
 * @property string $id_liquidacion
 * @property string $fecha
 * @property string $tramo
 * @property string $kg_origen
 * @property string $kg_destino
 * @property string $precio
 * @property string $valor
 */
class LiquidacionFlete extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacion_fletes';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_liquidacion',
        'fecha',
        'tramo',
        'kg_origen',
        'kg_destino',
        'precio',
        'valor'
    ];

    protected $casts = [
        'id_liquidacion' => 'string',
        'fecha' => 'string',
        'tramo' => 'string',
        'kg_origen' => 'string',
        'kg_destino' => 'string',
        'precio' => 'string',
        'valor' => 'string'
    ];

    public static $rules = [
        'fecha' => 'required',
        'tramo' => 'required',
        'valor' => 'required'
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'id_liquidacion');
    }
}
