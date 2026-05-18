<?php

namespace App\Http\Controllers;

use App\CashFlow\Actions\RecalculateCashFlowAction;
use App\Enums\CalculationTrigger;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct(
        private readonly RecalculateCashFlowAction $recalculate,
    ) {}

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $project = DB::transaction(function () use ($request) {
            $project = Project::create($request->validated());

            $this->seedDefaults($project);

            return $project;
        });

        $run = $this->recalculate->execute($project, CalculationTrigger::Initialisation);

        $project->load([
            'constructionInput',
            'scurveWeights',
            'saleInput',
            'operationInput',
            'opexInput',
        ]);
        $project->setRelation('latestCalculationRun', $run);

        return (new ProjectResource($project))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/projects/{project}
     */
    public function show(Project $project): ProjectResource
    {
        $project->load([
            'constructionInput',
            'scurveWeights',
            'saleInput',
            'operationInput',
            'opexInput',
            'latestCalculationRun.periodResults',
        ]);

        return new ProjectResource($project);
    }

    // ── Default seeding ────────────────────────────────────────────────────────

    /**
     * Seeds sensible default inputs based on the reference Excel workbook.
     * Defaults are model-aware: only seeds relevant input groups.
     */
    private function seedDefaults(Project $project): void
    {
        $model = $project->revenue_model;

        // Construction defaults (always required)
        $construction = $project->constructionInput()->create([
            'total_construction_cost'   => 100_000_000,
            'total_other_capex'         => 5_000_000,
            'construction_start_period' => 1,
            'construction_duration'     => 4,
        ]);

        // Default S-curve: [10%, 25%, 35%, 30%] across 4 construction years
        $defaultWeights = [
            ['construction_year' => 1, 'raw_weight' => 0.10],
            ['construction_year' => 2, 'raw_weight' => 0.25],
            ['construction_year' => 3, 'raw_weight' => 0.35],
            ['construction_year' => 4, 'raw_weight' => 0.30],
        ];

        foreach ($defaultWeights as $w) {
            $project->scurveWeights()->create($w);
        }

        // Sales defaults (Sale / Hybrid)
        if ($model->hasSales()) {
            $project->saleInput()->create([
                'gdv'                   => 150_000_000,
                'sales_start_period'    => 2,
                'sales_duration'        => 4,
                'sales_cost_percentage' => 0.02,
            ]);
        }

        // Operation defaults (Rental / Hybrid)
        if ($model->hasOperation()) {
            $project->operationInput()->create([
                'operation_start_period' => 5,
                'operation_duration'     => 6,
                'nsa'                    => 50_000,
                'rent_per_area'          => 1_000,
                'occupancy_rate'         => 0.90,
                'rental_growth_rate'     => 0.03,
                'exit_cap_rate'          => 0.08,
            ]);

            $project->opexInput()->create([
                'property_management_percentage' => 0.03,
                'insurance_percentage'           => 0.01,
                'maintenance_rate'               => 50,
                'utilities_rate'                 => 30,
                'fixed_marketing_cost'           => 500_000,
                'fixed_staffing_cost'            => 800_000,
            ]);
        }
    }
}