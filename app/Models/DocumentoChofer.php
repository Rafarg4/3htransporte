<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DocumentoChofer
 * @package App\Models
 *
 * @property int $id_chofer
 * @property string $nombre_archivo
 */
class DocumentoChofer extends Model
{
    public $table = 'documento_chofer';

    public $fillable = [
        'id_chofer',
        'nombre_archivo',
    ];

    protected $casts = [
        'id_chofer' => 'integer',
        'nombre_archivo' => 'string',
    ];

    public function chofer()
    {
        return $this->belongsTo(Chofer::class, 'id_chofer');
    }
}
