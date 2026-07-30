<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LiquidacionViatico
 * @package App\Models
 *
 * @property string $id_liquidacion
 * @property string $id_camion
 * @property string $fecha
 * @property string $descripcion
 * @property string $valor
 */
class LiquidacionViatico extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacion_viaticos';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_liquidacion',
        'id_camion',
        'fecha',
        'descripcion',
        'valor'
    ];

    protected $casts = [
        'id_liquidacion' => 'string',
        'id_camion' => 'string',
        'fecha' => 'string',
        'descripcion' => 'string',
        'valor' => 'string'
    ];

    public static $rules = [
        'id_camion' => 'required',
        'fecha' => 'required',
        'descripcion' => 'required',
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
