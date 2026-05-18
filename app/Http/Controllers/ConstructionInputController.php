<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\UpdateConstructionRequest;
use App\Http\Resources\CalculationRunResource;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class ConstructionInputController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    
    public function update(UpdateConstructionRequest $request, Project $project): CalculationRunResource
    {
        DB::transaction(function () use ($request, $project) {
            $data = $request->safe()->except('scurve_weights');

            if ($project->constructionInput) {
                $project->constructionInput->update($data);
            } else {
                $project->constructionInput()->create($data);
            }

            if ($request->has('scurve_weights')) {
                $project->scurveWeights()->delete();

                foreach ($request->input('scurve_weights') as $weight) {
                    $project->scurveWeights()->create([
                        'construction_year' => $weight['construction_year'],
                        'raw_weight'        => $weight['raw_weight'],
                    ]);
                }
            }
        });

        $trigger = $request->has('scurve_weights')
            ? CalculationTrigger::SCurve
            : CalculationTrigger::Construction;

        $run = $this->recalculate->execute($project, $trigger);

        return new CalculationRunResource($run);
    }
}