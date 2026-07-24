<?php

namespace App\Repositories;

use App\Models\ValeCombustible;
use App\Repositories\BaseRepository;

/**
 * Class ValeCombustibleRepository
 * @package App\Repositories
 * @version July 23, 2026, 11:28 pm UTC
*/

class ValeCombustibleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'numero_vale',
        'vigencia_desde',
        'vigencia_hasta',
        'id_camion',
        'nombre_estacion',
        'codigo',
        'direccion',
        'producto',
        'importe',
        'litros',
        'realizado_por'
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
        return ValeCombustible::class;
    }
}
