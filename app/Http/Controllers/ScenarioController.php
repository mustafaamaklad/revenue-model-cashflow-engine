<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\UpdateScenarioRequest;
use App\Http\Resources\CalculationRunResource;
use App\Models\Project;

class ScenarioController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    public function update(UpdateScenarioRequest $request, Project $project): CalculationRunResource
    {
        $project->update($request->validated());

        $run = $this->recalculate->execute($project, CalculationTrigger::ScenarioChange);

        return new CalculationRunResource($run);
    }
}