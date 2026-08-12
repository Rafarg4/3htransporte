<?php

namespace App\Repositories;

use App\Models\Direccion;
use App\Repositories\BaseRepository;

/**
 * Class DireccionRepository
 * @package App\Repositories
 */
class DireccionRepository extends BaseRepository
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
        return Direccion::class;
    }
}
