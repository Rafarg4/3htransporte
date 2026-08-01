<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Liquidacion
 * @package App\Models
 *
 * @property string $id_cliente
 * @property string $id_camion
 * @property string $id_chofer
 * @property string $id_orden_carga
 * @property string $fecha
 * @property string $estado
 */
class Liquidacion extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacions';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_cliente',
        'id_camion',
        'id_chofer',
        'id_orden_carga',
        'fecha',
        'estado'
    ];

    protected $casts = [
        'id_cliente' => 'string',
        'id_camion' => 'string',
        'id_chofer' => 'string',
        'id_orden_carga' => 'string',
        'fecha' => 'string',
        'estado' => 'string'
    ];

    public static $rules = [
        'id_cliente' => 'required',
        'id_camion' => 'required',
        'id_chofer' => 'required',
        'fecha' => 'required'
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

    public function ordenCarga()
    {
        return $this->belongsTo(OrdenCarga::class, 'id_orden_carga');
    }

    public function fletes()
    {
        return $this->hasMany(LiquidacionFlete::class, 'id_liquidacion');
    }

    public function descuentos()
    {
        return $this->hasMany(LiquidacionDescuento::class, 'id_liquidacion');
    }

    public function gastosAdministrativos()
    {
        return $this->hasMany(LiquidacionGastoAdministrativo::class, 'id_liquidacion');
    }

    public function viaticos()
    {
        return $this->hasMany(Viatico::class, 'id_liquidacion');
    }

    public function combustibles()
    {
        return $this->hasMany(ValeCombustible::class, 'id_liquidacion');
    }

    public function getTotalCreditosAttribute()
    {
        return $this->fletes->sum(function ($flete) {
            return (float) $flete->valor;
        });
    }

    public function getTotalDebitosAttribute()
    {
        $descuentos = $this->descuentos->sum(function ($item) {
            return (float) $item->valor;
        });

        $gastos = $this->gastosAdministrativos->sum(function ($item) {
            return (float) $item->valor;
        });

        $viaticos = $this->viaticos->sum(function ($item) {
            return (float) $item->monto;
        });

        $combustibles = $this->combustibles->sum(function ($item) {
            return (float) $item->litros * (float) $item->importe;
        });

        $recargos = $this->fletes->sum(function ($item) {
            return (float) $item->recargo;
        });

        return $descuentos + $gastos + $viaticos + $combustibles + $recargos;
    }

    public function getSaldoAttribute()
    {
        return $this->total_creditos - $this->total_debitos;
    }

    /**
     * Chapa del camion de esta liquidacion, para mostrar en el listado.
     *
     * @return string|null
     */
    public function getChapasAttribute()
    {
        return $this->camion->chapa ?? null;
    }
}
