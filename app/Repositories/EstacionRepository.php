<?php

namespace App\Repositories;

use App\Models\Estacion;
use App\Repositories\BaseRepository;

/**
 * Class EstacionRepository
 * @package App\Repositories
 */
class EstacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre'
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
        return Estacion::class;
    }
}
