<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 * ConstructionEndPeriod = ConstructionStartPeriod + ConstructionDuration - 1
 * TotalSCurveWeight = sum(SCurveWeight_t)
 * NormalizedSCurveWeight_t = SCurveWeight_t / TotalSCurveWeight
 * 
 * If t < ConstructionStartPeriod, then ConstructionOutflow_t = 0
 * If t > ConstructionEndPeriod, then ConstructionOutflow_t = 0
 * If ConstructionStartPeriod <= t <= ConstructionEndPeriod, then ConstructionOutflow_t applies
 * 
 *   ConstructionOutflow_t = TotalConstructionCost x ConstructionSCurveWeight_t
 *   OtherCapEx_t = TotalOtherCapEx x ConstructionSCurveWeight_t
 */
final class ConstructionCalculator
{
    /**
     * Normalise the raw s-curve weights.
     */
    public function normalizeScurve(CalculationContext $ctx): array
    {
        $raw          = $ctx->rawScurveWeights;
        $totalRaw     = array_sum($raw);
        $startPeriod  = $ctx->constructionStartPeriod;
        $endPeriod    = $ctx->constructionEndPeriod();
        $totalPeriods = $ctx->resolvedProjectPeriod();

        $normalized = [];

        for ($t = 1; $t <= $totalPeriods; $t++) {
            if ($t < $startPeriod || $t > $endPeriod) {
                $normalized[$t] = 0.0;
                continue;
            }

            $constructionYear = $t - $startPeriod + 1;

            if ($totalRaw > 0 && isset($raw[$constructionYear])) {
                $normalized[$t] = $raw[$constructionYear] / $totalRaw;
            } else {
                $normalized[$t] = 0.0;
            }
        }

        return $normalized;
    }

    /**
     * Populate construction outflow and other CapEx on each PeriodResult.
     */
    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        $t                = $period->period;
        $normalizedWeight = $ctx->normalizedScurveWeights[$t] ?? 0.0;

        $period->constructionScurveWeight = $normalizedWeight;
        $period->constructionOutflow      = $ctx->totalConstructionCost * $normalizedWeight;

        // CapEx uses same weight as construction (no separate CapEx curve in current scope)
        $period->capexWeight = $normalizedWeight;
        $period->otherCapex  = $ctx->totalOtherCapex * $normalizedWeight;
    }
}
