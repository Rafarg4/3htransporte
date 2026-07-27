<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
        // name y password se validan en el HTML5 del formulario
        // (resources/views/users/fields.blade.php). El email requiere chequeo
        // de unicidad contra la base de datos, que no se puede hacer por HTML5.
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }
}
