<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalStoreRequest extends FormRequest
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
        return [
            'apellidos' => 'bail|required|min:3|max:45|string',
            'nombres' => 'bail|required|min:3|max:45|string',
            'dni' => 'bail|required|digits:8|unique:personal_servicios',
            'direccion' => 'bail|nullable|max:145',
            'fechaNacimiento' => 'bail|required|date',
            'telefono' => 'bail|nullable|max:30',
            'correo' => 'bail|nullable|email|max:50',
            'fechaIngreso' => 'bail|required|date',
            'cargo_id' => 'bail|required|integer|min:1',
            'grupo' => 'bail|required',
            'fechaCese' => 'bail|nullable|date',
            'observacion' => 'bail|nullable|max:150',
        ];
    }
    public function messages()
    {
        return [
            'cargo_id.min'=> 'Seleccione un Cargo',
            'grupo.required'=> 'Seleccione un Grupo',
        ];
    }
}
