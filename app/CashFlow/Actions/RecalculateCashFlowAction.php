<?php

namespace App\CashFlow\Actions;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\Pipes\BuildTimelinePipe;
use App\CashFlow\Pipes\CalculateCashFlowPipe;
use App\CashFlow\Pipes\CalculateConstructionPipe;
use App\CashFlow\Pipes\CalculateNOIPipe;
use App\CashFlow\Pipes\CalculateOperationPipe;
use App\CashFlow\Pipes\CalculateOpexPipe;
use App\CashFlow\Pipes\CalculateSalesPipe;
use App\CashFlow\Pipes\CalculateTerminalValuePipe;
use App\CashFlow\Pipes\PersistResultsPipe;
use App\Enums\CalculationTrigger;
use App\Models\CalculationRun;
use App\Models\Project;
use Illuminate\Pipeline\Pipeline;

/**
 * Entry point for every recalculation.
 *
 *  1. Loads the project with all its input relations
 *  2. Builds a CalculationContext DTO
 *  3. Runs the pipeline
 *  4. Returns the persisted CalculationRun (with its period results)
 */
final class RecalculateCashFlowAction
{
    public function __construct(private readonly Pipeline $pipeline) {}

    public function execute(Project $project, CalculationTrigger $trigger): CalculationRun
    {
        $project->loadMissing([
            'constructionInput',
            'scurveWeights',
            'saleInput',
            'operationInput',
            'opexInput',
        ]);

        $ctx = CalculationContext::fromProject($project);

        $this->pipeline
            ->send($ctx)
            ->through([
                BuildTimelinePipe::class,
                CalculateConstructionPipe::class,
                CalculateSalesPipe::class,
                CalculateOperationPipe::class,
                CalculateOpexPipe::class,
                CalculateNOIPipe::class,
                CalculateTerminalValuePipe::class,
                CalculateCashFlowPipe::class,
                new PersistResultsPipe($trigger),
            ])
            ->thenReturn();

        /** @var CalculationRun $run */
        $run = $project->getRelation('latestCalculationRun');
        $run->load('periodResults');

        return $run;
    }
}