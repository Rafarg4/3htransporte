<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Empresa
 * @package App\Models
 * @version July 21, 2026, 9:32 pm UTC
 *
 * @property string $ruc
 * @property string $nombre
 * @property string $logo
 * @property string $direccion
 * @property string $telefono
 */
class Empresa extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'empresas';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'ruc',
        'nombre',
        'logo',
        'direccion',
        'telefono'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'ruc' => 'string',
        'nombre' => 'string',
        'logo' => 'string',
        'direccion' => 'string',
        'telefono' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // ruc, nombre, direccion y telefono se validan en el HTML5 del formulario
        // (resources/views/empresas/fields.blade.php). La regla de 'logo' (tipo/tamaño
        // de archivo) se define en Create/UpdateEmpresaRequest, que sí se mantiene en el backend.
    ];

    
}
