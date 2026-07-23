<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DocumentoCliente
 * @package App\Models
 *
 * @property int $id_cliente
 * @property string $nombre_archivo
 */
class DocumentoCliente extends Model
{
    public $table = 'documento_clientes';

    public $fillable = [
        'id_cliente',
        'nombre_archivo',
    ];

    protected $casts = [
        'id_cliente' => 'integer',
        'nombre_archivo' => 'string',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}
