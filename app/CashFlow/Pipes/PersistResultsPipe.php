<?php

namespace App\CashFlow\Pipes;

use App\CashFlow\DTO\CalculationContext;
use App\Enums\CalculationTrigger;
use App\Models\CalculationPeriodResult;
use App\Models\CalculationRun;
use Closure;
use Illuminate\Support\Facades\DB;

/**
 * Terminal pipe: save the runs
 */
final class PersistResultsPipe
{
    public function __construct(private readonly CalculationTrigger $trigger) {}

    public function handle(CalculationContext $ctx, Closure $next): CalculationContext
    {
        DB::transaction(function () use ($ctx) {
            /** @var CalculationRun $run */
            $run = CalculationRun::create([
                'project_id' => $ctx->project->id,
                'triggered_by' => $this->trigger->value,
                'revenue_model' => $ctx->revenueModel->value,
                'project_period' => $ctx->resolvedProjectPeriod(),
            ]);

            $rows = [];
            foreach ($ctx->periodResults as $p) {
                $rows[] = [
                    'calculation_run_id'         => $run->id,
                    'project_id'                 => $ctx->project->id,
                    'period'                     => $p->period,
                    'construction_scurve_weight' => $p->constructionScurveWeight,
                    'construction_outflow'       => $p->constructionOutflow,
                    'capex_weight'               => $p->capexWeight,
                    'other_capex'                => $p->otherCapex,
                    'sales_weight'               => $p->salesWeight,
                    'sales_inflow'               => $p->salesInflow,
                    'sales_cost'                 => $p->salesCost,
                    'operation_flag'             => $p->operationFlag,
                    'operation_year_index'       => $p->operationYearIndex,
                    'base_rental_income'         => $p->baseRentalIncome,
                    'rental_income'              => $p->rentalIncome,
                    'property_management_cost'   => $p->propertyManagementCost,
                    'insurance_cost'             => $p->insuranceCost,
                    'maintenance_cost'           => $p->maintenanceCost,
                    'utilities_cost'             => $p->utilitiesCost,
                    'marketing_cost'             => $p->marketingCost,
                    'staffing_cost'              => $p->staffingCost,
                    'opex'                       => $p->opex,
                    'noi'                        => $p->noi,
                    'terminal_value'             => $p->terminalValue,
                    'project_cash_flow'          => $p->projectCashFlow,
                    'created_at'                 => now(),
                    'updated_at'                 => now(),
                ];
            }

            CalculationPeriodResult::insert($rows);

            // Store run reference so the action can access it
            $ctx->project->setRelation('latestCalculationRun', $run);
        });

        return $next($ctx);
    }
}
