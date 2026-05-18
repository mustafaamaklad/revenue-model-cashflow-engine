<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 * Only if t = OperationEndPeriod: TerminalValue_t = NOI_t / ExitCapRate
 * If t != OperationEndPeriod: TerminalValue_t = 0
 * ExitCapRate > 0
 * After NOICalculator.
 */
final class TerminalValueCalculator
{
    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        if (!$ctx->revenueModel->hasOperation()) {
            $period->terminalValue = 0.0;
            return;
        }

        $opEnd       = $ctx->operationEndPeriod();
        $exitCapRate = $ctx->exitCapRate;

        if ($opEnd === null || $exitCapRate === null || $exitCapRate <= 0) {
            $period->terminalValue = 0.0;
            return;
        }

        $period->terminalValue = ($period->period === $opEnd)
            ? $period->noi / $exitCapRate
            : 0.0;
    }
}