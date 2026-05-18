<?php

namespace App\Providers;

use App\CashFlow\Calculators\CashFlowCalculator;
use App\CashFlow\Calculators\ConstructionCalculator;
use App\CashFlow\Calculators\NOICalculator;
use App\CashFlow\Calculators\OperationCalculator;
use App\CashFlow\Calculators\OpexCalculator;
use App\CashFlow\Calculators\SalesCalculator;
use App\CashFlow\Calculators\TerminalValueCalculator;
use App\CashFlow\Pipes\CalculateCashFlowPipe;
use App\CashFlow\Pipes\CalculateConstructionPipe;
use App\CashFlow\Pipes\CalculateNOIPipe;
use App\CashFlow\Pipes\CalculateOperationPipe;
use App\CashFlow\Pipes\CalculateOpexPipe;
use App\CashFlow\Pipes\CalculateSalesPipe;
use App\CashFlow\Pipes\CalculateTerminalValuePipe;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ConstructionCalculator::class);
        $this->app->singleton(SalesCalculator::class);
        $this->app->singleton(OperationCalculator::class);
        $this->app->singleton(OpexCalculator::class);
        $this->app->singleton(NOICalculator::class);
        $this->app->singleton(TerminalValueCalculator::class);
        $this->app->singleton(CashFlowCalculator::class);

        $this->app->singleton(CalculateConstructionPipe::class);
        $this->app->singleton(CalculateSalesPipe::class);
        $this->app->singleton(CalculateOperationPipe::class);
        $this->app->singleton(CalculateOpexPipe::class);
        $this->app->singleton(CalculateNOIPipe::class);
        $this->app->singleton(CalculateTerminalValuePipe::class);
        $this->app->singleton(CalculateCashFlowPipe::class);

    }

    public function boot(): void
    {
        //
    }
}