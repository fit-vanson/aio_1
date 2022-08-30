<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
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
            'user' =>'unique:users,name',
            'email' =>'unique:users,email'
        ];
    }
    public function messages()
    {
        return [
          'name.unique'=>'Tên người dùng đã tồn tại',
          'email.unique'=>'Email đã tồn tại',
        ];
    }
}
