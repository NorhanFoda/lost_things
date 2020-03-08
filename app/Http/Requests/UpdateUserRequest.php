<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'phone' => 'required_without:email',
        ];
    }

    public function messages(){
        return [
            'name.required' => trans('admin.name_required'),
            'email.required_without' => trans('admin.email_required_without'),
            'phone.required_without' => trans('admin.phone_required_without'),
        ];
    }
}
