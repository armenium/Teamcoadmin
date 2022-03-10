<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeUser extends FormRequest
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
        switch ($this->method()) {
            case 'POST':
                $rules = [
                    'name'     => 'required|string',
                    'email'    => 'required|string|email|unique:users',
                    'password' => 'unique:users|required|string|min:6|confirmed'
                ];
                break;
            case 'PUT':
                $rules = [
                    'name'     => 'required|string',
                    'password' => 'unique:users|required|string|min:6|confirmed'
                ];
                break;
            case 'PATCH':
                $rules = [
                    'name'     => 'required|string',
                    'email' => 'required|unique:users,email,'.$this->get('id'),
                    'password' => 'unique:users|required|string|min:6|confirmed'
                ];
                break;
            
            default:
                break;
        }
        return $rules;
    }
}
