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
        'fecha',
        'estado'
    ];

    protected $casts = [
        'id_cliente' => 'string',
        'fecha' => 'string',
        'estado' => 'string'
    ];

    public static $rules = [
        'id_cliente' => 'required',
        'fecha' => 'required'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
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
        return $this->hasMany(LiquidacionViatico::class, 'id_liquidacion');
    }

    public function combustibles()
    {
        return $this->hasMany(LiquidacionCombustible::class, 'id_liquidacion');
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
            return (float) $item->valor;
        });

        $combustibles = $this->combustibles->sum(function ($item) {
            return (float) $item->valor;
        });

        return $descuentos + $gastos + $viaticos + $combustibles;
    }

    public function getSaldoAttribute()
    {
        return $this->total_creditos - $this->total_debitos;
    }

    /**
     * Chapas usadas en las lineas de esta liquidacion, para mostrar en el listado.
     *
     * @return string
     */
    public function getChapasAttribute()
    {
        return $this->fletes
            ->concat($this->descuentos)
            ->concat($this->gastosAdministrativos)
            ->concat($this->viaticos)
            ->concat($this->combustibles)
            ->map(function ($linea) {
                return $linea->camion->chapa ?? null;
            })
            ->filter()
            ->unique()
            ->implode(', ');
    }
}
