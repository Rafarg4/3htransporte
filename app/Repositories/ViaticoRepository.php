<?php

namespace App\Repositories;

use App\Models\Viatico;
use App\Repositories\BaseRepository;

/**
 * Class ViaticoRepository
 * @package App\Repositories
 * @version July 28, 2026, 8:29 pm UTC
*/

class ViaticoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'numero',
        'fecha',
        'id_chofer',
        'numero_remision',
        'descripcion',
        'id_orden_carga',
        'cargado_por',
        'estado'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Viatico::class;
    }
}
