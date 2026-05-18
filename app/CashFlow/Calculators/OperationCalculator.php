<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 *   OperationFlag_t = 1 if OperationStartPeriod <= t <= OperationEndPeriod
 *   OperationYearIndex_t = t - OperationStartPeriod
 *   BaseRentalIncome = NSA x RentPerArea x OccupancyRate
 *   RentalIncome_t = BaseRentalIncome x (1 + RentalGrowthRate) ^ OperationYearIndex_t x OperationFlag_t
 */
final class OperationCalculator
{
    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        if (! $ctx->revenueModel->hasOperation()) {
            return;
        }

        $t       = $period->period;
        $opStart = $ctx->operationStartPeriod;
        $opEnd   = $ctx->operationEndPeriod();

        if ($opStart === null || $opEnd === null) {
            return;
        }

        $flag  = ($t >= $opStart && $t <= $opEnd) ? 1 : 0;
        $index = ($flag === 1) ? ($t - $opStart) : 0;

        $nsa         = $ctx->nsa ?? 0.0;
        $rentPerArea = $ctx->rentPerArea ?? 0.0;
        $occupancy   = $ctx->occupancyRate ?? 1.0;     // default 100%
        $growthRate  = $ctx->rentalGrowthRate ?? 0.0;  // default 0%

        $baseRent     = $nsa * $rentPerArea * $occupancy;
        $rentalIncome = $flag === 1
            ? $baseRent * (1 + $growthRate) ** $index
            : 0.0;

        $period->operationFlag      = $flag;
        $period->operationYearIndex = $index;
        $period->baseRentalIncome   = $baseRent;
        $period->rentalIncome       = $rentalIncome;
    }
}
