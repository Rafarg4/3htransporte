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
 * @property string $id_camion
 * @property string $id_orden_carga
 * @property string $fecha
 * @property string $tramo
 * @property string $kg_origen
 * @property string $kg_destino
 * @property string $diferencia
 * @property string $precio
 * @property string $valor
 * @property string $recargo_tolerancia
 * @property string $recargo_precio
 * @property string $recargo
 */
class LiquidacionFlete extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacion_fletes';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_liquidacion',
        'id_camion',
        'id_orden_carga',
        'fecha',
        'tramo',
        'kg_origen',
        'kg_destino',
        'diferencia',
        'precio',
        'valor',
        'recargo_tolerancia',
        'recargo_precio',
        'recargo'
    ];

    protected $casts = [
        'id_liquidacion' => 'string',
        'id_camion' => 'string',
        'id_orden_carga' => 'string',
        'fecha' => 'string',
        'tramo' => 'string',
        'kg_origen' => 'string',
        'kg_destino' => 'string',
        'diferencia' => 'string',
        'precio' => 'string',
        'valor' => 'string',
        'recargo_tolerancia' => 'string',
        'recargo_precio' => 'string',
        'recargo' => 'string'
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

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }

    public function ordenCarga()
    {
        return $this->belongsTo(OrdenCarga::class, 'id_orden_carga');
    }
}
