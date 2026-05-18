<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\ConstructionCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateConstructionPipe
{
    public function __construct(
        private readonly ConstructionCalculator $calculator,
    ) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        $ctx->normalizedScurveWeights = $this->calculator->normalizeScurve($ctx);

        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}