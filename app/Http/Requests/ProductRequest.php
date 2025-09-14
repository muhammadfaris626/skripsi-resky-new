<?php

namespace App\Http\Requests;

use App\Models\Product;
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
        $productId = $this->route('product') ?? $this->input('product_id');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'product_code' => [
                'required',
                'string',
                'max:50',
            ],
            'product_name' => ['required', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'product_code.required' => 'Product code is required.',
            'product_code.unique' => 'Product code already exists.',
            'product_code.max' => 'Product code must not exceed 50 characters.',
            'product_name.required' => 'Product name is required.',
            'product_name.max' => 'Product name must not exceed 255 characters.',
            'purchase_price.required' => 'Purchase price is required.',
            'purchase_price.numeric' => 'Purchase price must be a number.',
            'purchase_price.min' => 'Purchase price cannot be negative.',
            'selling_price.required' => 'Selling price is required.',
            'selling_price.numeric' => 'Selling price must be a number.',
            'selling_price.min' => 'Selling price cannot be negative.',
            'stock.required' => 'Stock is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock cannot be negative.',
        ];
    }
}
