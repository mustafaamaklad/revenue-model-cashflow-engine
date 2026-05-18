<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;
use Closure;

final class BuildTimelinePipe
{
    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        $totalPeriods = $ctx->resolvedProjectPeriod();

        $ctx->periodResults = [];

        for ($t = 1; $t <= $totalPeriods; $t++) {
            $ctx->periodResults[$t] = new PeriodResult(period: $t);
        }

        return $next($ctx);
    }
}