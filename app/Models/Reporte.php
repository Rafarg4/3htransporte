<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Reporte
 * @package App\Models
 * @version August 6, 2026, 1:36 am UTC
 *
 * @property string $kg_origen
 * @property string $kg_llegada
 * @property string $precio_real_flete
 * @property string $precio_fletero
 */
class Reporte extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'reportes';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'kg_origen',
        'kg_llegada',
        'precio_real_flete',
        'precio_fletero'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'kg_origen' => 'string',
        'kg_llegada' => 'string',
        'precio_real_flete' => 'string',
        'precio_fletero' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'kg_origen' => 'required',
        'kg_llegada' => 'required',
        'precio_real_flete' => 'required',
        'precio_fletero' => 'required'
    ];

    
}
