<?php

namespace App\Repositories;

use App\Models\Chofer;
use App\Repositories\BaseRepository;

/**
 * Class ChoferRepository
 * @package App\Repositories
 * @version July 22, 2026, 7:42 pm UTC
*/

class ChoferRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento',
        'nombre',
        'apellido',
        'documento',
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
        return Chofer::class;
    }
}
