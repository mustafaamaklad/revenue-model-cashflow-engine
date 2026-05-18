<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'operation_start_period' => ['sometimes', 'integer', 'min:1'],
            'operation_duration'     => ['sometimes', 'integer', 'min:1'],
            'nsa'                    => ['sometimes', 'numeric', 'min:0'],
            'rent_per_area'          => ['sometimes', 'numeric', 'min:0'],
            'occupancy_rate'         => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'rental_growth_rate'     => ['sometimes', 'numeric', 'min:-1', 'max:10'],
            'exit_cap_rate'          => ['sometimes', 'numeric', 'gt:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'exit_cap_rate.gt'        => 'Exit cap rate must be greater than zero (division by zero is invalid).',
            'occupancy_rate.max'      => 'Occupancy rate must be between 0 and 1 (e.g. 0.90 = 90%).',
        ];
    }
}