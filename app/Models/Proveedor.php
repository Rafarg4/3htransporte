<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Proveedor
 * @package App\Models
 * @version July 23, 2026, 12:47 am UTC
 *
 * @property string $documento
 * @property string $nombre
 */
class Proveedor extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'proveedors';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'documento',
        'nombre'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento' => 'string',
        'nombre' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'documento' => 'required',
        'nombre' => 'required'
    ];

    
}
