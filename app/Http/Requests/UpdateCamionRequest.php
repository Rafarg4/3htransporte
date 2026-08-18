<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Camion;

class UpdateCamionRequest extends FormRequest
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
        $rules = Camion::$rules;

        $idCamion = $this->route('camion');

        return array_merge($rules, [
            'chapa' => 'required|string|max:255|unique:camions,chapa,' . $idCamion . ',id,deleted_at,NULL',
            'documentos' => 'nullable|array',
            'documentos.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'chapa.unique' => 'Ya existe un camión creado con esta chapa.',
        ];
    }
}
