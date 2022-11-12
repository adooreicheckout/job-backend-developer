<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(int|string|null $id = null, ?string $idcolumn = 'id'): array
    {
        $id = $id ?: $this->route('product');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($id, $idcolumn)
            ],
            'price' => ['required', 'regex:/^\d*(\.\d{1,2})?$/'],
            'description' => ['required', 'string', 'max:3000'],
            'category' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'price.regex' => 'The :attribute must be currency with two decimal.'
        ];
    }
}
