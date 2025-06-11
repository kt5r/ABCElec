<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock_quantity' => ['required_if:manage_stock,1', 'nullable', 'integer', 'min:0'],
            'manage_stock' => ['boolean'],
            'in_stock' => ['boolean'],
            'featured' => ['boolean'],
            'status' => ['boolean'],
            'featured_image' => ['nullable', 'image', 'max:2048'], // 2MB max
        ];

        // Add unique SKU rule for create
        if ($this->isMethod('POST')) {
            $rules['sku'][] = 'unique:products,sku';
        } else {
            $rules['sku'][] = Rule::unique('products', 'sku')->ignore($this->product);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The product name is required.'),
            'sku.required' => __('The SKU is required.'),
            'sku.unique' => __('This SKU is already in use.'),
            'category_id.required' => __('Please select a category.'),
            'category_id.exists' => __('The selected category is invalid.'),
            'description.required' => __('The product description is required.'),
            'price.required' => __('The price is required.'),
            'price.numeric' => __('The price must be a number.'),
            'price.min' => __('The price must be greater than or equal to 0.'),
            'sale_price.numeric' => __('The sale price must be a number.'),
            'sale_price.min' => __('The sale price must be greater than or equal to 0.'),
            'sale_price.lt' => __('The sale price must be less than the regular price.'),
            'stock_quantity.required_if' => __('The stock quantity is required when managing stock.'),
            'stock_quantity.integer' => __('The stock quantity must be a whole number.'),
            'stock_quantity.min' => __('The stock quantity must be greater than or equal to 0.'),
            'featured_image.image' => __('The file must be an image.'),
            'featured_image.max' => __('The image size must not exceed 2MB.'),
        ];
    }
}