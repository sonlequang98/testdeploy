<?php

namespace Backend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PutReleaseNumberRequest extends FormRequest
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
            'release_number_name'   =>  'required|max:16',
            'image'  =>  'required'
        ];
    }
}
