<?php

namespace App\Http\Requests;

use App\Enums\RevenueModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'revenue_model' => ['required', Rule::enum(RevenueModel::class)],
            'project_period' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}