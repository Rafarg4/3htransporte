<?php

namespace App\Repositories;

use App\Models\Proveedor;
use App\Repositories\BaseRepository;

/**
 * Class ProveedorRepository
 * @package App\Repositories
 * @version July 23, 2026, 12:47 am UTC
*/

class ProveedorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'documento',
        'nombre',
        'apellido'
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
        return Proveedor::class;
    }
}
