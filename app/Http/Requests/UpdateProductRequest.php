<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|min:3|max:255',
            'price' => 'nullable|numeric',
            'brand' => 'nullable|min:3|max:255',
            'image' => 'nullable|image|max:512',
        ];
    }
}
