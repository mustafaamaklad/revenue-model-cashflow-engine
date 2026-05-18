<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\UpdateOperationRequest;
use App\Http\Resources\CalculationRunResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class OperationInputController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    public function update(UpdateOperationRequest $request, Project $project): CalculationRunResource|JsonResponse
    {
        if (!$project->revenue_model->hasOperation()) {
            return response()->json([
                'message' => 'Operation inputs do not apply to a Sale-only project.',
            ], 422);
        }

        $data = $request->validated();

        if ($project->operationInput) {
            $project->operationInput->update($data);
        } else {
            $project->operationInput()->create($data);
        }

        $run = $this->recalculate->execute($project, CalculationTrigger::Operation);

        return new CalculationRunResource($run);
    }
}