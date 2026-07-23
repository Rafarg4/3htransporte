<?php

namespace App\Repositories;

use App\Models\Camion;
use App\Repositories\BaseRepository;

/**
 * Class CamionRepository
 * @package App\Repositories
 * @version July 22, 2026, 8:36 pm UTC
*/

class CamionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'marca',
        'modelo',
        'tipo',
        'anho',
        'color',
        'ejes',
        'nro_chasis',
        'id_chofer',
        'chapa'
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
        return Camion::class;
    }
}
