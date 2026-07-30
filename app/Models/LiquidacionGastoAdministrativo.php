<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LiquidacionGastoAdministrativo
 * @package App\Models
 *
 * @property string $id_liquidacion
 * @property string $fecha
 * @property string $concepto
 * @property string $valor
 */
class LiquidacionGastoAdministrativo extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'liquidacion_gastos_administrativos';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'id_liquidacion',
        'fecha',
        'concepto',
        'valor'
    ];

    protected $casts = [
        'id_liquidacion' => 'string',
        'fecha' => 'string',
        'concepto' => 'string',
        'valor' => 'string'
    ];

    public static $rules = [
        'fecha' => 'required',
        'concepto' => 'required',
        'valor' => 'required'
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'id_liquidacion');
    }
}
