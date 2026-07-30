<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Viatico
 * @package App\Models
 * @version July 28, 2026, 8:29 pm UTC
 *
 * @property string $numero
 * @property string $fecha
 * @property string $id_chofer
 * @property string $descripcion
 * @property string $monto
 * @property string $id_orden_carga
 * @property string $cargado_por
 * @property string $estado
 * @property string $id_liquidacion
 * @property string $liquidado
 */
class Viatico extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'viaticos';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'numero',
        'fecha',
        'id_chofer',
        'numero_remision',
        'descripcion',
        'monto',
        'id_orden_carga',
        'cargado_por',
        'estado',
        'id_liquidacion',
        'liquidado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'numero' => 'string',
        'fecha' => 'string',
        'id_chofer' => 'string',
        'numero_remision' => 'string',
        'descripcion' => 'string',
        'monto' => 'string',
        'id_orden_carga' => 'string',
        'cargado_por' => 'string',
        'estado' => 'string',
        'id_liquidacion' => 'string',
        'liquidado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'numero' => 'required',
        'fecha' => 'required',
        'id_chofer' => 'required',
        'descripcion' => 'required',
        'monto' => 'required',
        'id_orden_carga' => 'required',
        'cargado_por' => 'required'
    ];

    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'id_chofer');
    }

    public function ordenCarga()
    {
        return $this->belongsTo(OrdenCarga::class, 'id_orden_carga');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoViatico::class, 'id_viatico');
    }

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'id_liquidacion');
    }
}
