<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class UpdateConstructionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'total_construction_cost'   => ['sometimes', 'numeric', 'min:0'],
            'total_other_capex'         => ['sometimes', 'numeric', 'min:0'],
            'construction_start_period' => ['sometimes', 'integer', 'min:1'],
            'construction_duration'     => ['required_with:scurve_weights', 'integer', 'min:1'],

            'scurve_frequency'                   => ['required_with:scurve_weights', 'in:yearly,monthly,quarterly'],
            'scurve_weights'                     => ['sometimes', 'array', 'min:1'],
            'scurve_weights.*.construction_year' => ['required_with:scurve_weights', 'integer', 'min:1', 'distinct'],
            'scurve_weights.*.raw_weight'        => ['required_with:scurve_weights', 'numeric', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            if ($this->has('scurve_weights')) {
                $converted = $this->convertScurveWeightsFrequencyIntoYearly(
                    $this->input('scurve_weights'),
                    $this->input('scurve_frequency')
                );

                $this->merge(['scurve_weights' => $converted]);

                if (count($this->input('scurve_weights')) !== $this->input('construction_duration')) {
                    $validator->errors()->add(
                        'scurve_weights',
                        'The count of weights in years must match the construction duration'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'scurve_weights.*.construction_year.required_with' => 'Each S-curve entry must include a construction_year.',
            'scurve_weights.*.raw_weight.required_with'        => 'Each S-curve entry must include a raw_weight.',
        ];
    }

    protected function convertScurveWeightsFrequencyIntoYearly(
        array $weights,
        string $frequency
    ): array {
        $weights = Arr::sort($weights, 'construction_year');

        if ($frequency === 'yearly') {
            return $weights;
        }

        $factor = match ($frequency) {
            'monthly' => 12,
            'quarterly' => 4,
            default => throw ValidationException::withMessages(
                ['scurve_frequency' => "Unsupported scurve frequency: {$frequency}"]
            ),
        };

        if (count($weights) % $factor !== 0) {
            throw ValidationException::withMessages(
                ['scurve_weights' => "Weights count must match a destribution based on {$frequency} frequency"]
            );
        }

        $rawWeights = array_column($weights, 'raw_weight');

        $converted = [];

        foreach (array_chunk($rawWeights, $factor) as $index => $chunk) {
            $converted[] = [
                'construction_year' => $index + 1,
                'raw_weight' => array_sum($chunk),
            ];
        }

        return $converted;
    }
}
