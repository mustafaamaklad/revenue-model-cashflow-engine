<?php

namespace App\Http\Requests;

use App\Enums\RevenueModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScenarioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'revenue_model'  => ['sometimes', Rule::enum(RevenueModel::class)],
            'project_period' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}