<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class OrdenCarga
 * @package App\Models
 * @version July 23, 2026, 1:07 am UTC
 *
 * @property string $id_proveedor
 * @property string $id_producto
 * @property string $origen
 * @property string $destino
 * @property string $id_camion
 * @property string $carreta
 * @property string $estado
 */
class OrdenCarga extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'orden_cargas';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'id_proveedor',
        'id_producto',
        'origen',
        'destino',
        'id_camion',
        'carreta',
        'estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_proveedor' => 'string',
        'id_producto' => 'string',
        'origen' => 'string',
        'destino' => 'string',
        'id_camion' => 'string',
        'carreta' => 'string',
        'estado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // Validado en el HTML5 del formulario (resources/views/orden_cargas/fields.blade.php).
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }
}
