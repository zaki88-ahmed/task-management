<?php

namespace App\Http\Requests;

use App\Models\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|min:6|same:password',
            'gender'=> Rule::in(['male', 'female']),
            'address'=>'required',
            'education'=>'required',
            'phone_no'=>'string|max:15',
            'date_of_birth'=>'date_format|nullable',
        ];
    }
}
