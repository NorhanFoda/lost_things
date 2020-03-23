<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoneSignUpRequest extends FormRequest
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
            'name' => 'required',
            'password' => 'required|min:6|max:14|confirmed',
            'phone' => ['required','unique:users','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/']
        ];
    }
    
    public function messages(){
        return [
            'name.required' => trans('admin.name_required'),
            'password.min' => trans('admin.password_min'),
            'password.required_with' => trans('admin.required_with'),
            'password.same' => trans('admin.password_same'),
            'password_confirmation.min' => trans('admin.password_confirmation_min'),
            'phone.required' => trans('admin.phone_required'),
            'phone.unique' => trans('admin.phone_unique'),
            'phone.regex' => trans('admin.phone_regex')
        ];  
    }
}
