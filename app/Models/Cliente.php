<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Cliente
 * @package App\Models
 * @version July 21, 2026, 9:18 pm UTC
 *
 * @property string $nombre
 * @property string $apellido
 * @property string $documento
 * @property string $direccion
 * @property string $telefono
 * @property string $id_document_cliente
 */
class Cliente extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'clientes';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'nombre',
        'apellido',
        'tipo_propietario',
        'documento',
        'direccion',
        'telefono',
        'id_document_cliente'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        'apellido' => 'string',
        'tipo_propietario' => 'string',
        'documento' => 'string',
        'direccion' => 'string',
        'telefono' => 'string',

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // Validado en el HTML5 del formulario (resources/views/clientes/fields.blade.php).
    ];

    public function documentos()
    {
        return $this->hasMany(DocumentoCliente::class, 'id_cliente');
    }

    public function camiones()
    {
        return $this->hasMany(Camion::class, 'id_cliente');
    }
}
