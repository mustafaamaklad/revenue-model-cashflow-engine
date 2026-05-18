<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalculationPeriodResult extends Model
{
    protected $fillable = [
        'calculation_run_id',
        'project_id',
        'period',
        'construction_scurve_weight',
        'construction_outflow',
        'capex_weight',
        'other_capex',
        'sales_weight',
        'sales_inflow',
        'sales_cost',
        'operation_flag',
        'operation_year_index',
        'base_rental_income',
        'rental_income',
        'property_management_cost',
        'insurance_cost',
        'maintenance_cost',
        'utilities_cost',
        'marketing_cost',
        'staffing_cost',
        'opex',
        'noi',
        'terminal_value',
        'project_cash_flow',
    ];

    protected $casts = [
        'period'                      => 'integer',
        'operation_flag'              => 'integer',
        'operation_year_index'        => 'integer',
        'construction_scurve_weight'  => 'decimal:8',
        'construction_outflow'        => 'decimal:4',
        'capex_weight'                => 'decimal:8',
        'other_capex'                 => 'decimal:4',
        'sales_weight'                => 'decimal:8',
        'sales_inflow'                => 'decimal:4',
        'sales_cost'                  => 'decimal:4',
        'base_rental_income'          => 'decimal:4',
        'rental_income'               => 'decimal:4',
        'property_management_cost'    => 'decimal:4',
        'insurance_cost'              => 'decimal:4',
        'maintenance_cost'            => 'decimal:4',
        'utilities_cost'              => 'decimal:4',
        'marketing_cost'              => 'decimal:4',
        'staffing_cost'               => 'decimal:4',
        'opex'                        => 'decimal:4',
        'noi'                         => 'decimal:4',
        'terminal_value'              => 'decimal:4',
        'project_cash_flow'           => 'decimal:4',
    ];

    public function calculationRun(): BelongsTo
    {
        return $this->belongsTo(CalculationRun::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}