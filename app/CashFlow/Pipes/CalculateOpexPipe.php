<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\OpexCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateOpexPipe
{
    public function __construct(
        private readonly OpexCalculator $calculator,
    ) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}