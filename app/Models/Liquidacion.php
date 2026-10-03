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
 * @property string $facturado
 * @property string $pagado
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
        'estado',
        'facturado',
        'pagado',
        'moneda',
        'monto_moneda',
        'cotizacion_usd'
    ];

    protected $casts = [
        'id_cliente' => 'string',
        'id_camion' => 'string',
        'id_chofer' => 'string',
        'id_orden_carga' => 'string',
        'fecha' => 'string',
        'estado' => 'string',
        'facturado' => 'string',
        'pagado' => 'string',
        'moneda' => 'string',
        'monto_moneda' => 'float',
        'cotizacion_usd' => 'float'
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

        $cotizacionUsd = $this->cotizacionDolar();

        $viaticos = $this->viaticos->sum(function ($item) use ($cotizacionUsd) {
            return $item->montoEnGuaranies($cotizacionUsd);
        });

        $combustibles = $this->combustibles->sum(function ($item) use ($cotizacionUsd) {
            return $item->valorEnGuaranies($cotizacionUsd);
        });

        return $descuentos + $gastos + $viaticos + $combustibles;
    }

    /**
     * Cotizacion del dolar con la que se pasan a guaranies los viaticos/vales cargados en USD:
     * la congelada al liquidar; si la liquidacion es anterior a ese campo, la de la liquidacion
     * en USD o, en ultimo caso, la vigente en Monedas.
     */
    public function cotizacionDolar()
    {
        if ((float) $this->cotizacion_usd > 0) {
            return (float) $this->cotizacion_usd;
        }

        if ($this->moneda === 'USD' && (float) $this->monto_moneda > 0) {
            return (float) $this->monto_moneda;
        }

        static $vigente = null;
        if ($vigente === null) {
            $vigente = (float) optional(Moneda::where('tipo_moneda', 'USD')->orderByDesc('id')->first())->cotizacion;
        }

        return $vigente;
    }

    public function getSaldoAttribute()
    {
        return $this->total_creditos - $this->total_debitos;
    }

    /**
     * Formatea un monto en guaranies segun la moneda de la liquidacion: sin moneda queda en Gs.
     * como siempre; con moneda se divide por la cotizacion guardada (monto_moneda) y se agrega el codigo.
     */
    public function formatearMonto($valor)
    {
        if ($this->moneda && $this->moneda !== 'PYG' && (float) $this->monto_moneda > 0) {
            return number_format((float) $valor / (float) $this->monto_moneda, 2, ',', '.') . ' ' . $this->moneda;
        }

        return number_format((float) $valor, 0, ',', '.');
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
