<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\UpdateSalesRequest;
use App\Http\Resources\CalculationRunResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class SalesInputController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    public function update(UpdateSalesRequest $request, Project $project): CalculationRunResource|JsonResponse
    {
        if (!$project->revenue_model->hasSales()) {
            return response()->json([
                'message' => 'Sales inputs do not apply to a Rental-only project.',
            ], 422);
        }

        $data = $request->validated();

        if ($project->saleInput) {
            $project->saleInput->update($data);
        } else {
            $project->saleInput()->create($data);
        }

        $run = $this->recalculate->execute($project, CalculationTrigger::Sales);

        return new CalculationRunResource($run);
    }
}