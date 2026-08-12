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

            'chofer_ids' => 'required|array|min:1',
            'chofer_ids.*' => 'exists:chofers,id',

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

            // Gasto Administrativo: idem. El "valor" que se guarda es el total (monto_unitario x
            // cantidad de fletes), calculado en JS; monto_unitario no se persiste, solo se valida
            // por si el campo fue manipulado.
            'gasto_administrativo.fecha' => 'nullable|date',
            'gasto_administrativo.concepto' => 'required_with:gasto_administrativo.fecha,gasto_administrativo.valor',
            'gasto_administrativo.monto_unitario' => 'nullable|numeric',
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
            'fecha' => 'fecha',
            'facturado' => 'facturado',

            'camion_ids' => 'camión',
            'camion_ids.*' => 'camión',
            'chofer_ids' => 'chofer',
            'chofer_ids.*' => 'chofer',

            'flete.*.fecha' => 'fecha del flete',
            'flete.*.tramo' => 'tramo del flete',
            'flete.*.kg_origen' => 'kg origen del flete',
            'flete.*.kg_destino' => 'kg destino del flete',
            'flete.*.diferencia' => 'diferencia del flete',
            'flete.*.precio' => 'precio del flete',
            'flete.*.valor' => 'valor del flete',
            'flete.*.recargo_tolerancia' => 'tolerancia del recargo',
            'flete.*.recargo_precio' => 'precio del recargo',

            'orden_carga.*' => 'orden de carga',

            'descuento_auto.*.fecha' => 'fecha del descuento automático',
            'descuento_auto.*.valor' => 'valor del descuento automático',

            'descuento.fecha' => 'fecha del descuento',
            'descuento.concepto' => 'concepto del descuento',
            'descuento.valor' => 'valor del descuento',

            'gasto_administrativo.fecha' => 'fecha del gasto administrativo',
            'gasto_administrativo.concepto' => 'concepto del gasto administrativo',
            'gasto_administrativo.monto_unitario' => 'monto del gasto administrativo',
            'gasto_administrativo.valor' => 'valor del gasto administrativo',

            'viatico_ids' => 'viático',
            'viatico_ids.*' => 'viático',
            'vale_combustible_ids' => 'vale de combustible',
            'vale_combustible_ids.*' => 'vale de combustible',
        ];
    }

    /**
     * Spanish messages for every validation rule used in rules(), ya que el idioma por
     * defecto de la aplicacion (config/app.php locale=en) no tiene traduccion instalada.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_with' => 'El campo :attribute es obligatorio.',
            'exists' => 'El :attribute seleccionado no es válido.',
            'array' => 'El campo :attribute debe ser una lista.',
            'min.array' => 'Tildá al menos un :attribute.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'facturado.in' => 'Indicá si la liquidación está facturada (Si o No).',
        ];
    }
}
