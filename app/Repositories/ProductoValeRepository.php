<?php

namespace App\Repositories;

use App\Models\ProductoVale;
use App\Repositories\BaseRepository;

/**
 * Class ProductoValeRepository
 * @package App\Repositories
 */
class ProductoValeRepository extends BaseRepository
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
        return ProductoVale::class;
    }
}
