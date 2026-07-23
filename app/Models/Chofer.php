<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Chofer
 * @package App\Models
 * @version July 22, 2026, 7:42 pm UTC
 *
 * @property string $documento
 * @property string $nombre
 * @property string $documento
 * @property string $estado
 */
class Chofer extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'chofers';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'documento',
        'nombre',
        'apellido',
        'documento',
        'estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'documento' => 'string',
        'nombre' => 'string',
        'apellido' => 'string',
        'documento' => 'string',
        'estado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function documentos()
    {
        return $this->hasMany(DocumentoChofer::class, 'id_chofer');
    }
}
