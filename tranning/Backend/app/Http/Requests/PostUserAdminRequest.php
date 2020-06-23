<?php

namespace Backend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUserAdminRequest extends FormRequest
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
            'name'  =>  'required|max:100',
            'email' =>  'required|max:64:unique:users',
            'password'  =>  'required'
        ];
    }
}
