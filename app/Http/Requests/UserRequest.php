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
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:14'
        ];
    }

    // public function messages(){
    //     return [
    //       'name.required' => trans('routes.name_required'),
    //       'email.unique' => trans('routes.email_required'),
    //       'email.email' => trans('routes.email'),
    //       'email.unique' => trans('routes.unique_email'),
    //       'password.required' => trans('routes.password_required'),
    //       'password.min' => trans('routes.password_min'),
    //       'password.max' => trans('routes.password_max'),
    //     ];
    // }
}
