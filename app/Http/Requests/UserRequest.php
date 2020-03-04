<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'email' => 'required_without:phone',
            // 'phone' => ['required_without:email','regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'phone' => 'required_without:email',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6',
        ];
    }

    public function messages(){
        return [
            'name.required' => trans('admin.name_required'),
            'email.required_without' => trans('admin.email_required_without'),
            'email.email' => trans('admin.email_incorrect'),
            'phone.required_without' => trans('admin.phone_required_without'),
            'phone.regex' => trans('admin.phone_regex'),
            'password.min' => trans('admin.password_min'),
            'password.required_with' => trans('admin.required_with'),
            'password.same' => trans('admin.password_same'),
            'password_confirmation.min' => trans('admin.password_confirmation_min'),
        ];
    }
}
