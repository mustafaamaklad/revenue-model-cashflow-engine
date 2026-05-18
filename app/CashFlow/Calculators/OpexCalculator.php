<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\CalculationContext;
use App\CashFlow\DTO\PeriodResult;

/**
 * Handles all OPEX components and total OPEX.
 *
 * PropertyManagementCost_t = RentalIncome_t x PropertyManagementPercentage
 * InsuranceCost_t = RentalIncome_t x InsurancePercentage
 * MaintenanceCost_t = NSA x MaintenanceRate
 * UtilitiesCost_t = NSA x UtilitiesRate
 * MarketingCost_t = FixedMarketingCost
 * StaffingCost_t = FixedStaffingCost
 *
 * OPEX_t = (PropertyManagementCost_t +
 *  InsuranceCost_t +
 *  MaintenanceCost_t +
 *  UtilitiesCost_t +
 *  MarketingCost_t +
 *  StaffingCost_t) x OperationFlag_t
 * 
 */
final class OpexCalculator
{
    public function calculate(CalculationContext $ctx, PeriodResult $period): void
    {
        if (! $ctx->revenueModel->hasOperation() || $period->operationFlag === 0) {
            // All OPEX components remain zero
            return;
        }

        $nsa          = $ctx->nsa ?? 0.0;
        $rentalIncome = $period->rentalIncome;

        $pm          = $rentalIncome * ($ctx->propertyManagementPercentage ?? 0.0);
        $insurance   = $rentalIncome * ($ctx->insurancePercentage ?? 0.0);
        $maintenance = $nsa * ($ctx->maintenanceRate ?? 0.0);
        $utilities   = $nsa * ($ctx->utilitiesRate ?? 0.0);
        $marketing   = $ctx->fixedMarketingCost ?? 0.0;
        $staffing    = $ctx->fixedStaffingCost ?? 0.0;

        $totalOpex = $pm + $insurance + $maintenance + $utilities + $marketing + $staffing;

        $period->propertyManagementCost = $pm;
        $period->insuranceCost          = $insurance;
        $period->maintenanceCost        = $maintenance;
        $period->utilitiesCost          = $utilities;
        $period->marketingCost          = $marketing;
        $period->staffingCost           = $staffing;
        $period->opex                   = $totalOpex;
    }
}
