<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeriodResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period' => $this->period,

            'construction' => [
                'scurve_weight'    => (float) $this->construction_scurve_weight,
                'outflow'          => (float) $this->construction_outflow,
                'capex_weight'     => (float) $this->capex_weight,
                'other_capex'      => (float) $this->other_capex,
                'total_outflow'    => (float) $this->construction_outflow + (float) $this->other_capex,
            ],

            'sales' => [
                'weight'   => (float) $this->sales_weight,
                'inflow'   => (float) $this->sales_inflow,
                'cost'     => (float) $this->sales_cost,
                'net'      => (float) $this->sales_inflow - (float) $this->sales_cost,
            ],

            'operation' => [
                'flag'             => (int)   $this->operation_flag,
                'year_index'       => (int)   $this->operation_year_index,
                'base_rental_income' => (float) $this->base_rental_income,
                'rental_income'    => (float) $this->rental_income,
            ],

            'opex' => [
                'property_management' => (float) $this->property_management_cost,
                'insurance'           => (float) $this->insurance_cost,
                'maintenance'         => (float) $this->maintenance_cost,
                'utilities'           => (float) $this->utilities_cost,
                'marketing'           => (float) $this->marketing_cost,
                'staffing'            => (float) $this->staffing_cost,
                'total'               => (float) $this->opex,
            ],

            'noi'           => (float) $this->noi,
            'terminal_value' => (float) $this->terminal_value,

            'project_cash_flow' => (float) $this->project_cash_flow,
        ];
    }
}