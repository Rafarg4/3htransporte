<?php

namespace App\Repositories;

use App\Models\Reporte;
use App\Repositories\BaseRepository;

/**
 * Class ReporteRepository
 * @package App\Repositories
 * @version August 6, 2026, 1:36 am UTC
*/

class ReporteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nro_remision',
        'tramo',
        'kg_origen',
        'kg_llegada',
        'precio',
        'monto'
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
        return Reporte::class;
    }
}
