<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 * NOI = RentalIncome_t − OPEX_t
 *
 * If the revenue model is Sale-only, NOI must be zero 
 */
final class NOICalculator
{
    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        if (!$ctx->revenueModel->hasOperation() || $period->operationFlag === 0) {
            $period->noi = 0.0;
            return;
        }

        $period->noi = $period->rentalIncome - $period->opex;
    }
}