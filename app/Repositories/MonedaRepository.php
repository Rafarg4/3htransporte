<?php

namespace App\Repositories;

use App\Models\Moneda;
use App\Repositories\BaseRepository;

/**
 * Class MonedaRepository
 * @package App\Repositories
 * @version September 24, 2026, 4:59 pm UTC
*/

class MonedaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipo_moneda',
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
        return Moneda::class;
    }
}
