<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\NOICalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateNOIPipe
{
    public function __construct(private readonly NOICalculator $calculator) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}