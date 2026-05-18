<?php

namespace App\Models;

use App\Enums\CalculationTrigger;
use App\Enums\RevenueModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalculationRun extends Model
{
    protected $fillable = [
        'project_id',
        'triggered_by',
        'revenue_model',
        'project_period',
    ];

    protected $casts = [
        'triggered_by'   => CalculationTrigger::class,
        'revenue_model'  => RevenueModel::class,
        'project_period' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function periodResults(): HasMany
    {
        return $this->hasMany(CalculationPeriodResult::class)->orderBy('period');
    }


    public function getTotalConstructionOutflowAttribute(): float
    {
        return (float) $this->periodResults->sum('construction_outflow');
    }

    public function getTotalOtherCapexAttribute(): float
    {
        return (float) $this->periodResults->sum('other_capex');
    }

    public function getTotalSalesInflowAttribute(): float
    {
        return (float) $this->periodResults->sum('sales_inflow');
    }

    public function getTotalSalesCostAttribute(): float
    {
        return (float) $this->periodResults->sum('sales_cost');
    }

    public function getTotalRentalIncomeAttribute(): float
    {
        return (float) $this->periodResults->sum('rental_income');
    }

    public function getTotalOpexAttribute(): float
    {
        return (float) $this->periodResults->sum('opex');
    }

    public function getTotalNoiAttribute(): float
    {
        return (float) $this->periodResults->sum('noi');
    }

    public function getTotalTerminalValueAttribute(): float
    {
        return (float) $this->periodResults->sum('terminal_value');
    }

    public function getTotalProjectCashFlowAttribute(): float
    {
        return (float) $this->periodResults->sum('project_cash_flow');
    }
}   