<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'gdv'                   => ['sometimes', 'numeric', 'min:0'],
            'sales_start_period'    => ['sometimes', 'integer', 'min:1'],
            'sales_duration'        => ['sometimes', 'integer', 'min:1'],
            'sales_cost_percentage' => ['sometimes', 'numeric', 'min:0', 'max:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'sales_cost_percentage.max' => 'Sales cost percentage must be between 0 and 1 (e.g. 0.02 = 2%).',
        ];
    }
}