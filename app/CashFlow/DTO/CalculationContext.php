<?php

namespace App\CashFlow\DTO;

use App\Enums\RevenueModel;
use App\Models\Project;

/**
 * Passed though the pipeline and mutated by each pipe
 */
final class CalculationContext
{
    public array $normalizedScurveWeights = [];

    public array $normalizedSalesWeights = [];

    public array $periodResults = [];

    public function __construct(
        public readonly Project $project,
        public readonly RevenueModel $revenueModel,
        public readonly int $projectPeriod,

        public readonly float $totalConstructionCost,
        public readonly int $constructionStartPeriod,
        public readonly int $constructionDuration,
        public readonly float $totalOtherCapex,
        public readonly array $rawScurveWeights,

        public readonly ?float $gdv,
        public readonly ?int $salesStartPeriod,
        public readonly ?int $salesDuration,
        public readonly ?float $salesCostPercentage,

        public readonly ?int $operationStartPeriod,
        public readonly ?int $operationDuration,
        public readonly ?float $nsa,
        public readonly ?float $rentPerArea,
        public readonly ?float $occupancyRate,
        public readonly ?float $rentalGrowthRate,
        public readonly ?float $exitCapRate,

        public readonly ?float $propertyManagementPercentage,
        public readonly ?float $insurancePercentage,
        public readonly ?float $maintenanceRate,
        public readonly ?float $utilitiesRate,
        public readonly ?float $fixedMarketingCost,
        public readonly ?float $fixedStaffingCost,
    ) {}

    public function constructionEndPeriod(): int
    {
        return $this->constructionStartPeriod + $this->constructionDuration - 1;
    }

    public function salesEndPeriod(): ?int
    {
        if ($this->salesStartPeriod === null || $this->salesDuration === null) {
            return null;
        }
        return $this->salesStartPeriod + $this->salesDuration - 1;
    }

    public function operationEndPeriod(): ?int
    {
        if ($this->operationStartPeriod === null || $this->operationDuration === null) {
            return null;
        }
        return $this->operationStartPeriod + $this->operationDuration - 1;
    }

    public function resolvedProjectPeriod(): int
    {
        return $this->projectPeriod;
    }

    public static function fromProject(Project $project): self
    {
        $c    = $project->constructionInput;
        $sale = $project->saleInput;
        $op   = $project->operationInput;
        $opex = $project->opexInput;

        $rawWeights = $c
            ? $project->scurveWeights->pluck('raw_weight', 'construction_year')->all()
            : [];

        return new self(
            project      : $project,
            revenueModel : $project->revenue_model,
            projectPeriod: $project->project_period,

            totalConstructionCost  : $c?->total_construction_cost ?? 0,
            constructionStartPeriod: $c?->construction_start_period ?? 1,
            constructionDuration   : $c?->construction_duration ?? 1,
            totalOtherCapex        : $c?->total_other_capex ?? 0,
            rawScurveWeights       : $rawWeights,

            gdv                : $sale?->gdv,
            salesStartPeriod   : $sale?->sales_start_period,
            salesDuration      : $sale?->sales_duration,
            salesCostPercentage: $sale?->sales_cost_percentage,

            operationStartPeriod: $op?->operation_start_period,
            operationDuration   : $op?->operation_duration,
            nsa                 : $op?->nsa,
            rentPerArea         : $op?->rent_per_area,
            occupancyRate       : $op?->occupancy_rate,
            rentalGrowthRate    : $op?->rental_growth_rate,
            exitCapRate         : $op?->exit_cap_rate,

            propertyManagementPercentage: $opex?->property_management_percentage,
            insurancePercentage         : $opex?->insurance_percentage,
            maintenanceRate             : $opex?->maintenance_rate,
            utilitiesRate               : $opex?->utilities_rate,
            fixedMarketingCost          : $opex?->fixed_marketing_cost,
            fixedStaffingCost           : $opex?->fixed_staffing_cost,
        );
    }
}