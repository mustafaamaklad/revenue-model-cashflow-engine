<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOpexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'property_management_percentage' => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'insurance_percentage'           => ['sometimes', 'numeric', 'min:0', 'max:1'],
            'maintenance_rate'               => ['sometimes', 'numeric', 'min:0'],
            'utilities_rate'                 => ['sometimes', 'numeric', 'min:0'],
            'fixed_marketing_cost'           => ['sometimes', 'numeric', 'min:0'],
            'fixed_staffing_cost'            => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}