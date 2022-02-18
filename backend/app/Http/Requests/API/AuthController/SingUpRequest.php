<?php

namespace App\Http\Requests\API\AuthController;

use Illuminate\Foundation\Http\FormRequest;

class SingUpRequest extends FormRequest
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
            'name' => 'required|string|max:180|min:3',
            'email' => 'required|string|email|unique:users|max:180',
            'password' => 'required|string|max:180|min:6',
            'password_confirm'=> 'required|same:password|max:180'
        ];
    }
}
