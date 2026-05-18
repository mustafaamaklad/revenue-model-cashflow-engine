<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $c    = $this->constructionInput;
        $sale = $this->saleInput;
        $op   = $this->operationInput;
        $opex = $this->opexInput;

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'revenue_model'  => $this->revenue_model->value,
            'project_period' => $this->project_period,
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),

            'inputs' => [
                'construction' => $c ? [
                    'total_construction_cost'   => (float) $c->total_construction_cost,
                    'total_other_capex'         => (float) $c->total_other_capex,
                    'construction_start_period' => $c->construction_start_period,
                    'construction_duration'     => $c->construction_duration,
                    'construction_end_period'   => $c->construction_end_period,
                    'scurve_weights'            => $this->scurveWeights->map(fn ($w) => [
                        'construction_year' => $w->construction_year,
                        'raw_weight'        => (float) $w->raw_weight,
                    ]),
                ] : null,

                'sales' => $sale ? [
                    'gdv'                   => (float) $sale->gdv,
                    'sales_start_period'    => $sale->sales_start_period,
                    'sales_duration'        => $sale->sales_duration,
                    'sales_end_period'      => $sale->sales_end_period,
                    'sales_cost_percentage' => (float) $sale->sales_cost_percentage,
                ] : null,

                'operation' => $op ? [
                    'operation_start_period' => $op->operation_start_period,
                    'operation_duration'     => $op->operation_duration,
                    'operation_end_period'   => $op->operation_end_period,
                    'nsa'                    => (float) $op->nsa,
                    'rent_per_area'          => (float) $op->rent_per_area,
                    'occupancy_rate'         => (float) $op->occupancy_rate,
                    'rental_growth_rate'     => (float) $op->rental_growth_rate,
                    'exit_cap_rate'          => (float) $op->exit_cap_rate,
                ] : null,

                'opex' => $opex ? [
                    'property_management_percentage' => (float) $opex->property_management_percentage,
                    'insurance_percentage'           => (float) $opex->insurance_percentage,
                    'maintenance_rate'               => (float) $opex->maintenance_rate,
                    'utilities_rate'                 => (float) $opex->utilities_rate,
                    'fixed_marketing_cost'           => (float) $opex->fixed_marketing_cost,
                    'fixed_staffing_cost'            => (float) $opex->fixed_staffing_cost,
                ] : null,
            ],

            'latest_calculation_run' => $this->whenLoaded(
                'latestCalculationRun',
                fn () => new CalculationRunResource($this->latestCalculationRun),
            ),
        ];
    }
}