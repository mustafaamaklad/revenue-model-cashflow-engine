<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\OperationCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateOperationPipe
{
    public function __construct(
        private readonly OperationCalculator $calculator,
    ) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}