<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\CashFlowCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateCashFlowPipe
{
    public function __construct(private readonly CashFlowCalculator $calculator) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($period);
        }

        return $next($ctx);
    }
}