<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // public function rules(): array
    // {
    //     return [
    //         //
    //     ];
    // }

      /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'product_name'        => 'required|string|max:255',
            'product_price'       => 'required|numeric|min:1',
            'product_description' => 'required|string',
            'product_image'       => 'nullable|array',
            'product_image.*'     => 'image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    /**
     * Custom error messages (optional but professional)
     */
    public function messages(): array
    {
        return [
            'product_name.required'  => 'Product name is required.',
            'product_price.required' => 'Product price is required.',
            'product_price.numeric'  => 'Price must be a number.',
            'product_description.required' => 'Description is required.',
            'product_image.*.image'  => 'Each file must be an image.',
        ];
    }
}
