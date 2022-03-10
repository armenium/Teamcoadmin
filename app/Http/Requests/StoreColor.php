<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColor extends FormRequest
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
            'name'       => 'required|unique:colors',
            'value_code' => 'required|unique:colors'
        ];
    }
    public function messages()
    {
        return [
            'name.required'       => 'A Color is required',
            'value_code.required' => 'An Hex value is required',
            'name.unique'         => 'This color has been saved before'
        ];
    }
}
