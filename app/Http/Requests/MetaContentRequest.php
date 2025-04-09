<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetaContentRequest extends FormRequest
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
            'title' => 'max:255|required',
            'slug' => 'max:255|required|unique:meta_contents,slug,'.request()->input('id').',id',
            'desc' => 'max:1024',
        ];
    }
}
