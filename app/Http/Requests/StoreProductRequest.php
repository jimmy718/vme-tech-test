<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'barcode' => 'required|numeric',
            'name' => 'required|min:3|max:255',
            'price' => 'required|numeric',
            'brand' => 'nullable|min:3|max:255',
            'image' => 'nullable|image|max:512'
        ];
    }
}
