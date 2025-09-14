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
        $productId = $this->input('id') ?? $this->route('product');

        return [
            'category_id' => 'required|exists:categories,id',
            'product_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Product::class, 'product_code')->ignore($productId)
            ],
            'product_name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0|max:999999999999.99',
            'selling_price' => 'required|numeric|min:0|max:999999999999.99|gte:purchase_price',
            'stock' => 'required|integer|min:0|max:999999999'
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean up numeric fields
        if ($this->has('purchase_price')) {
            $this->merge([
                'purchase_price' => (float) str_replace(',', '', $this->input('purchase_price'))
            ]);
        }

        if ($this->has('selling_price')) {
            $this->merge([
                'selling_price' => (float) str_replace(',', '', $this->input('selling_price'))
            ]);
        }

        if ($this->has('stock')) {
            $this->merge([
                'stock' => (int) str_replace(',', '', $this->input('stock'))
            ]);
        }
    }
}
