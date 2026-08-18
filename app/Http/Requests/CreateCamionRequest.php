<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Camion;

class CreateCamionRequest extends FormRequest
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
        return array_merge(Camion::$rules, [
            'chapa' => 'required|string|max:255|unique:camions,chapa,NULL,id,deleted_at,NULL',
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
