<?php

namespace Backend\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostImageRequest extends FormRequest
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
            'file'  =>  'max:10000'
        ];
    }

    public function messages()
    {
        return [
            'file.mimes'    =>  '発売号画像は「png」「jpg」のみ有効です。',
            'file.max'      =>  '発売号画像が10mb以下のみ有効です。'
        ];  
    }
}
