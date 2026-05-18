<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\UpdateOpexRequest;
use App\Http\Resources\CalculationRunResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class OpexInputController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    public function update(UpdateOpexRequest $request, Project $project): CalculationRunResource|JsonResponse
    {
        if (!$project->revenue_model->hasOperation()) {
            return response()->json([
                'message' => 'OPEX inputs do not apply to a Sale-only project.',
            ], 422);
        }

        $data = $request->validated();

        if ($project->opexInput) {
            $project->opexInput->update($data);
        } else {
            $project->opexInput()->create($data);
        }

        $run = $this->recalculate->execute($project, CalculationTrigger::Opex);

        return new CalculationRunResource($run);
    }
}