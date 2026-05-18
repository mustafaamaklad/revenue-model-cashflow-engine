<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculationRunResource;
use App\Models\CalculationRun;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CashFlowController extends Controller
{

    public function latest(Project $project): CalculationRunResource
    {
        $run = $project->latestCalculationRun()->with('periodResults')->firstOrFail();

        return new CalculationRunResource($run);
    }

    public function history(Project $project): AnonymousResourceCollection
    {
        $runs = $project->calculationRuns()
            ->with('periodResults')
            ->paginate(20);

        return CalculationRunResource::collection($runs);
    }

    public function show(Project $project, CalculationRun $run): CalculationRunResource
    {
        abort_if($run->project_id !== $project->id, 404);

        $run->load('periodResults');

        return new CalculationRunResource($run);
    }
}