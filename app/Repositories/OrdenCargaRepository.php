<?php

namespace App\Repositories;

use App\Models\OrdenCarga;
use App\Repositories\BaseRepository;

/**
 * Class OrdenCargaRepository
 * @package App\Repositories
 * @version July 23, 2026, 1:07 am UTC
*/

class OrdenCargaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_proveedor',
        'id_producto',
        'origen',
        'destino',
        'id_camion',
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
        return OrdenCarga::class;
    }
}
