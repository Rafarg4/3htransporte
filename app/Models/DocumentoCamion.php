<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DocumentoCamion
 * @package App\Models
 *
 * @property int $id_camion
 * @property string $nombre_archivo
 */
class DocumentoCamion extends Model
{
    public $table = 'documento_camiones';

    public $fillable = [
        'id_camion',
        'nombre_archivo',
    ];

    protected $casts = [
        'id_camion' => 'integer',
        'nombre_archivo' => 'string',
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class, 'id_camion');
    }
}
