<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateConstructionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'total_construction_cost'   => ['sometimes', 'numeric', 'min:0'],
            'total_other_capex'         => ['sometimes', 'numeric', 'min:0'],
            'construction_start_period' => ['sometimes', 'integer', 'min:1'],
            'construction_duration'     => ['sometimes', 'integer', 'min:1'],

            'scurve_weights'                         => ['sometimes', 'array', 'min:1'],
            'scurve_weights.*.construction_year'     => ['required_with:scurve_weights', 'integer', 'min:1'],
            'scurve_weights.*.raw_weight'            => ['required_with:scurve_weights', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            // $weights = $this->input('scurve_weights', []);

            // if (empty($weights)) {
            //     return;
            // }

            // // All raw weights must be positive so normalisation is meaningful
            // $allZero = collect($weights)->every(fn ($w) => ($w['raw_weight'] ?? 0) == 0);
            // if ($allZero) {
            //     $validator->errors()->add(
            //         'scurve_weights',
            //         'At least one S-curve weight must be greater than zero.'
            //     );
            // }

            
        });
    }

    public function messages(): array
    {
        return [
            'scurve_weights.*.construction_year.required_with' => 'Each S-curve entry must include a construction_year.',
            'scurve_weights.*.raw_weight.required_with'        => 'Each S-curve entry must include a raw_weight.',
        ];
    }
}