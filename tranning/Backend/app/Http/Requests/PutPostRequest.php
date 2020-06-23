<?php

namespace Backend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutPostRequest extends FormRequest
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
            'title' =>  'required|max:300|min:10',
            'content'   =>  'required',
            'category_parent_id'    =>  'required',
            'category_id'   =>  'required',
            'image_id'  =>  'required'
        ];
    }
}
