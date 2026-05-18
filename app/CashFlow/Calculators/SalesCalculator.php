<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 *  SalesEndPeriod = SalesStartPeriod + SalesDuration - 1
 *   RawWeight_i = : RawWeight_i = sin(pi x i / (N + 1))
 *   SalesInflow_t = GDV x SalesWeight_i
 *   i = t - SalesStartPeriod + 1
 *   SalesCost_t = SalesInflow_t x SalesCostPercentage
 */
final class SalesCalculator
{
    public function normalizeSalesWeights(int $salesDuration): array
    {
        $n         = $salesDuration;
        $rawSum    = 0.0;
        $rawValues = [];

        for ($i = 1; $i <= $n; $i++) {
            $raw           = sin(M_PI * $i / ($n + 1));
            $rawValues[$i] = $raw;
            $rawSum       += $raw;
        }

        $normalized = [];
        for ($i = 1; $i <= $n; $i++) {
            $normalized[$i] = $rawSum > 0 ? $rawValues[$i] / $rawSum : 0.0;
        }

        return $normalized;
    }

    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        if (!$ctx->revenueModel->hasSales()) {
            $period->salesWeight = 0.0;
            $period->salesInflow = 0.0;
            $period->salesCost   = 0.0;
            return;
        }

        $t             = $period->period;
        $salesStart    = $ctx->salesStartPeriod;
        $salesEnd      = $ctx->salesEndPeriod();
        $gdv           = $ctx->gdv ?? 0.0;
        $costPct       = $ctx->salesCostPercentage ?? 0.0;

        if ($salesStart === null || $salesEnd === null) {
            $period->salesWeight = 0.0;
            $period->salesInflow = 0.0;
            $period->salesCost   = 0.0;
            return;
        }

        if ($t < $salesStart || $t > $salesEnd) {
            $period->salesWeight = 0.0;
            $period->salesInflow = 0.0;
            $period->salesCost   = 0.0;
            return;
        }
 
        $i = $t - $salesStart + 1;

        $weight  = $ctx->normalizedSalesWeights[$i] ?? 0.0;
        $inflow  = $gdv * $weight;
        $cost    = $inflow * $costPct;

        $period->salesWeight = $weight;
        $period->salesInflow = $inflow;
        $period->salesCost   = $cost;
    }
}
