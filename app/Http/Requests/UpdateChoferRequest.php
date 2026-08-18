<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Chofer;

class UpdateChoferRequest extends FormRequest
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
        $rules = Chofer::$rules;

        $idChofer = $this->route('chofer');

        return array_merge($rules, [
            'documento' => 'required|string|max:255|unique:chofers,documento,' . $idChofer . ',id,deleted_at,NULL',
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
            'documento.unique' => 'Ya existe un chofer creado con este documento.',
        ];
    }
}
