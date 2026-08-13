<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Reporte
 * @package App\Models
 *
 * @property string $nro_remision
 * @property string $fecha
 * @property string $id_cliente
 * @property string $id_camion
 * @property string $id_chofer
 * @property string $id_producto
 * @property string $tramo
 * @property string $kg_origen
 * @property string $kg_llegada
 * @property string $precio
 * @property string $monto
 */
class Reporte extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'reportes';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'nro_remision',
        'fecha',
        'id_cliente',
        'id_camion',
        'id_chofer',
        'id_producto',
        'tramo',
        'kg_origen',
        'kg_llegada',
        'precio',
        'monto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nro_remision' => 'string',
        'fecha' => 'string',
        'id_cliente' => 'string',
        'id_camion' => 'string',
        'id_chofer' => 'string',
        'id_producto' => 'string',
        'tramo' => 'string',
        'kg_origen' => 'string',
        'kg_llegada' => 'string',
        'precio' => 'string',
        'monto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nro_remision' => 'required',
        'fecha' => 'required',
        'id_cliente' => 'required|exists:clientes,id',
        'id_camion' => 'required|exists:camions,id',
        'id_chofer' => 'required|exists:chofers,id',
        'id_producto' => 'required|exists:productos,id',
        'tramo' => 'required',
        'kg_origen' => 'required',
        'kg_llegada' => 'required',
        'precio' => 'required',
        'monto' => 'required'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }

    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'id_chofer');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}
