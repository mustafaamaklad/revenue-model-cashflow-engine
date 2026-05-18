<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\Calculators\TerminalValueCalculator;
use App\CashFlow\DTO\CalculationContext;
use Closure;

final class CalculateTerminalValuePipe
{
    public function __construct(private readonly TerminalValueCalculator $calculator) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        foreach ($ctx->periodResults as $period) {
            $this->calculator->calculate($ctx, $period);
        }

        return $next($ctx);
    }
}