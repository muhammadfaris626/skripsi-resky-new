<?php

namespace App\Http\Requests;

use App\Models\Target;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TargetRequest extends FormRequest
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
        $itemId = $this->input('id');
        return [
            'employee_id' => 'required',
            'month' => [
                'required',
                'date_format:Y-m',
                Rule::unique(Target::class, 'month')
                    ->where('employee_id', $this->input('employee_id'))
                    ->ignore($itemId)],
            'sale_target' => 'required|numeric|min:0|max:999999999999.99'
        ];
    }
}
