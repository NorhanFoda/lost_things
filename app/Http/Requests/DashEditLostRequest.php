<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashEditLostRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'location' => 'required',
            'place' => 'required',
            'category_id' => 'required',
        ];
    }
    
    public function messages(){
        return [
            'title.required' => trans('admin.title_required'),
            'description.required' => trans('admin.description_required'),
            'location.required' => trans('admin.location_required') ,
            'place.required' => trans('admin.place_required') ,
            'category_id.required' => trans('admin.category_id_required') ,
        ];
    }
}
