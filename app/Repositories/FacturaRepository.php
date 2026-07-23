<?php

namespace App\Repositories;

use App\Models\Factura;
use App\Repositories\BaseRepository;

/**
 * Class FacturaRepository
 * @package App\Repositories
 * @version January 24, 2026, 4:40 pm UTC
*/

class FacturaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'establecimineto',
        'punto',
        'numero',
        'tipodocumento',
        'condifcionpago',
        'moneda',
        'receiptid',
        'descripcion',
        'tipoemision',
        'tipotransaccion',
        'cliente',
        'ruc',
        'nombre',
        'cpais',
        'tipopago',
        'monto',
        'totalpago',
        'totalredondeo',
        'codigoseguridadaleatorio',
        'items'
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
        return Factura::class;
    }
}
