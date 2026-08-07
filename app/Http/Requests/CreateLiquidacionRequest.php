<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Liquidacion;

class CreateLiquidacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(Liquidacion::$rules, [
            'id_cliente' => 'exists:clientes,id',
            'id_camion' => 'exists:camions,id',
            'id_chofer' => 'exists:chofers,id',
            'id_orden_carga' => 'nullable|exists:orden_cargas,id',
            'facturado' => 'required|in:Si,No',

            // Flete: si se completa cualquier campo de la fila, Tramo y Valor pasan a ser obligatorios
            // (evita que guardarLinea() intente insertar la fila con "valor" vacío).
            'flete.fecha' => 'nullable|date',
            'flete.tramo' => 'required_with:flete.fecha,flete.kg_origen,flete.kg_destino,flete.precio,flete.valor',
            'flete.kg_origen' => 'nullable|numeric',
            'flete.kg_destino' => 'nullable|numeric',
            'flete.diferencia' => 'nullable|numeric',
            'flete.precio' => 'nullable|numeric',
            'flete.valor' => 'required_with:flete.fecha,flete.tramo,flete.kg_origen,flete.kg_destino,flete.precio|nullable|numeric',
            'flete.recargo_tolerancia' => 'nullable|numeric',
            'flete.recargo_precio' => 'nullable|numeric',

            // Descuento: si se completa cualquier campo, Concepto y Valor pasan a ser obligatorios.
            'descuento.fecha' => 'nullable|date',
            'descuento.concepto' => 'required_with:descuento.fecha,descuento.valor',
            'descuento.valor' => 'required_with:descuento.fecha,descuento.concepto|nullable|numeric',

            // Gasto Administrativo: idem.
            'gasto_administrativo.fecha' => 'nullable|date',
            'gasto_administrativo.concepto' => 'required_with:gasto_administrativo.fecha,gasto_administrativo.valor',
            'gasto_administrativo.valor' => 'required_with:gasto_administrativo.fecha,gasto_administrativo.concepto|nullable|numeric',

            'viatico_ids' => 'nullable|array',
            'viatico_ids.*' => 'integer|exists:viaticos,id',
            'vale_combustible_ids' => 'nullable|array',
            'vale_combustible_ids.*' => 'integer|exists:vale_combustibles,id',
        ]);
    }

    /**
     * Friendlier field names for the validation messages.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id_cliente' => 'propietario',
            'id_camion' => 'camión',
            'id_chofer' => 'chofer',
            'id_orden_carga' => 'orden de carga',
            'facturado' => 'facturado',
            'flete.tramo' => 'tramo del flete',
            'flete.valor' => 'valor del flete',
            'flete.diferencia' => 'diferencia del flete',
            'flete.recargo_tolerancia' => 'tolerancia del recargo',
            'flete.recargo_precio' => 'precio del recargo',
            'descuento.concepto' => 'concepto del descuento',
            'descuento.valor' => 'valor del descuento',
            'gasto_administrativo.concepto' => 'concepto del gasto administrativo',
            'gasto_administrativo.valor' => 'valor del gasto administrativo',
        ];
    }
}
