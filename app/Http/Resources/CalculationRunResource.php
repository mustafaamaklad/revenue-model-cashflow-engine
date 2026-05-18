<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalculationRunResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'project_id'     => $this->project_id,
            'triggered_by'   => $this->triggered_by->value,
            'revenue_model'  => $this->revenue_model->value,
            'project_period' => $this->project_period,
            'calculated_at'  => $this->created_at->toIso8601String(),

            // totals summary
            'totals' => [
                'construction_outflow' => (float) $this->total_construction_outflow,
                'other_capex'          => (float) $this->total_other_capex,
                'sales_inflow'         => (float) $this->total_sales_inflow,
                'sales_cost'           => (float) $this->total_sales_cost,
                'rental_income'        => (float) $this->total_rental_income,
                'opex'                 => (float) $this->total_opex,
                'noi'                  => (float) $this->total_noi,
                'terminal_value'       => (float) $this->total_terminal_value,
                'project_cash_flow'    => (float) $this->total_project_cash_flow,
            ],
            // each period result
            'periods' => PeriodResultResource::collection($this->periodResults),
        ];
    }
}