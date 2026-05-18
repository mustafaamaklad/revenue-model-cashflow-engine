<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\SalesCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateSalesPipe
{
    public function __construct(
        private readonly SalesCalculator $calculator,
    ) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        if ($ctx->revenueModel->hasSales() && $ctx->salesDuration !== null) {
            // Pre-compute normalised sine weights for all sales years
            $ctx->normalizedSalesWeights = $this->calculator->normalizeSalesWeights($ctx->salesDuration);
        }

        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}