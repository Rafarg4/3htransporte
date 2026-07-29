<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DocumentoViatico
 * @package App\Models
 *
 * @property int $id_viatico
 * @property string $nombre_archivo
 */
class DocumentoViatico extends Model
{
    public $table = 'documento_viatico';

    public $fillable = [
        'id_viatico',
        'nombre_archivo',
    ];

    protected $casts = [
        'id_viatico' => 'integer',
        'nombre_archivo' => 'string',
    ];

    public function viatico()
    {
        return $this->belongsTo(Viatico::class, 'id_viatico');
    }
}
