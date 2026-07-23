<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Factura
 * @package App\Models
 * @version January 24, 2026, 4:40 pm UTC
 *
 * @property string $fecha
 * @property string $establecimineto
 * @property string $punto
 * @property string $numero
 * @property string $tipodocumento
 * @property string $condifcionpago
 * @property string $moneda
 * @property string $receiptid
 * @property string $descripcion
 * @property string $tipoemision
 * @property string $tipotransaccion
 * @property string $cliente
 * @property string $ruc
 * @property string $nombre
 * @property string $cpais
 * @property string $tipopago
 * @property string $monto
 * @property string $totalpago
 * @property string $totalredondeo
 * @property string $codigoseguridadaleatorio
 * @property string $items
 */
class Factura extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'facturas';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'fecha',
        'establecimineto',
        'punto',
        'numero',
        'tipodocumento',
        'condifcionpago',
        'moneda',
        'receiptid',
        'descripcion',
        'tipoemision',
        'tipotransaccion',
        'cliente',
        'ruc',
        'nombre',
        'cpais',
        'tipopago',
        'monto',
        'totalpago',
        'totalredondeo',
        'codigoseguridadaleatorio',
        'items'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'string',
        'establecimineto' => 'string',
        'punto' => 'string',
        'numero' => 'string',
        'tipodocumento' => 'string',
        'condifcionpago' => 'string',
        'moneda' => 'string',
        'receiptid' => 'string',
        'descripcion' => 'string',
        'tipoemision' => 'string',
        'tipotransaccion' => 'string',
        'cliente' => 'string',
        'ruc' => 'string',
        'nombre' => 'string',
        'cpais' => 'string',
        'tipopago' => 'string',
        'monto' => 'string',
        'totalpago' => 'string',
        'totalredondeo' => 'string',
        'codigoseguridadaleatorio' => 'string',
        'items' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required',
        'establecimineto' => 'required',
        'punto' => 'required',
        'numero' => 'required',
        'tipodocumento' => 'required',
        'condifcionpago' => 'required',
        'moneda' => 'required',
        'receiptid' => 'required',
        'descripcion' => 'required',
        'tipoemision' => 'required',
        'tipotransaccion' => 'required',
        'cliente' => 'required',
        'ruc' => 'required',
        'nombre' => 'required',
        'cpais' => 'required',
        'tipopago' => 'requirerd',
        'monto' => 'requried',
        'totalpago' => 'required',
        'totalredondeo' => 'required',
        'codigoseguridadaleatorio' => 'required',
        'items' => 'required'
    ];

    
}
