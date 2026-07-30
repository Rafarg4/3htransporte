<?php

namespace App\Repositories;

use App\Models\Liquidacion;
use App\Repositories\BaseRepository;

/**
 * Class LiquidacionRepository
 * @package App\Repositories
 * @version July 30, 2026, 1:43 am UTC
*/

class LiquidacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id_cliente',
        'id_camion',
        'fecha',
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
        return Liquidacion::class;
    }
}
