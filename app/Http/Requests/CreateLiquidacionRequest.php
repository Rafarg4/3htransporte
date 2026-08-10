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
            'facturado' => 'required|in:Si,No',

            'camion_ids' => 'required|array|min:1',
            'camion_ids.*' => 'exists:camions,id',

            // Flete: uno por camion tildado (flete[<id_camion>][...]). Si se completa cualquier
            // campo de un bloque, Tramo y Valor pasan a ser obligatorios PARA ESE MISMO bloque
            // (Laravel resuelve las referencias flete.*.x contra el mismo indice, no cruzado).
            'flete.*.fecha' => 'nullable|date',
            'flete.*.tramo' => 'required_with:flete.*.fecha,flete.*.kg_origen,flete.*.kg_destino,flete.*.precio,flete.*.valor',
            'flete.*.kg_origen' => 'nullable|numeric',
            'flete.*.kg_destino' => 'nullable|numeric',
            'flete.*.diferencia' => 'nullable|numeric',
            'flete.*.precio' => 'nullable|numeric',
            'flete.*.valor' => 'required_with:flete.*.fecha,flete.*.tramo,flete.*.kg_origen,flete.*.kg_destino,flete.*.precio|nullable|numeric',
            'flete.*.recargo_tolerancia' => 'nullable|numeric',
            'flete.*.recargo_precio' => 'nullable|numeric',

            // Orden de Carga: una por camion tildado (opcional).
            'orden_carga.*' => 'nullable|exists:orden_cargas,id',

            // Descuento "Faltante de Carga" automatico por camion (concepto se fuerza en el servidor).
            'descuento_auto.*.fecha' => 'nullable|date',
            'descuento_auto.*.valor' => 'nullable|numeric',

            // Descuento manual: si se completa cualquier campo, Concepto y Valor pasan a ser obligatorios.
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
     * Extra validation that plain rules() can't express: the flete/orden_carga/descuento_auto
     * arrays are keyed by id_camion, and none of those columns have a real DB foreign key
     * (they're all `text`), so this is the only barrier against a tampered/stale camion id
     * that was never actually ticked in camion_ids.
     *
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $camionIds = array_map('strval', (array) $this->input('camion_ids', []));

            foreach (['flete', 'orden_carga', 'descuento_auto'] as $campo) {
                $claves = array_map('strval', array_keys((array) $this->input($campo, [])));

                foreach ($claves as $clave) {
                    if (!in_array($clave, $camionIds, true)) {
                        $validator->errors()->add($campo, 'Hay datos de ' . $campo . ' para una chapa que no fue seleccionada.');
                    }
                }
            }
        });
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
            'camion_ids' => 'camión',
            'facturado' => 'facturado',
            'flete.*.tramo' => 'tramo del flete',
            'flete.*.valor' => 'valor del flete',
            'flete.*.diferencia' => 'diferencia del flete',
            'flete.*.recargo_tolerancia' => 'tolerancia del recargo',
            'flete.*.recargo_precio' => 'precio del recargo',
            'descuento.concepto' => 'concepto del descuento',
            'descuento.valor' => 'valor del descuento',
            'gasto_administrativo.concepto' => 'concepto del gasto administrativo',
            'gasto_administrativo.valor' => 'valor del gasto administrativo',
        ];
    }
}
