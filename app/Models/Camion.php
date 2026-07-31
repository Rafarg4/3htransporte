<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Camion
 * @package App\Models
 * @version July 22, 2026, 8:36 pm UTC
 *
 * @property string $marca
 * @property string $modelo
 * @property string $nro_chasis
 * @property string $id_chofer
 * @property string $chapa
 * @property string $carreta_chapa
 * @property string $id_cliente
 * @property string $estado
 */
class Camion extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'camions';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'marca',
        'modelo',
        'tipo',
        'anho',
        'color',
        'ejes',
        'nro_chasis',
        'id_chofer',
        'chapa',
        'carreta_chapa',
        'id_cliente',
        'estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'marca' => 'string',
        'modelo' => 'string',
        'tipo' => 'string',
        'anho' => 'string',
        'color' => 'string',
        'ejes' => 'string',
        'nro_chasis' => 'string',
        'id_chofer' => 'string',
        'chapa' => 'string',
        'carreta_chapa' => 'string',
        'id_cliente' => 'string',
        'estado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // Validado en el HTML5 del formulario (resources/views/camions/fields.blade.php).
    ];

    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'id_chofer');
    }

    public function propietario()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoCamion::class, 'id_camion');
    }
}
