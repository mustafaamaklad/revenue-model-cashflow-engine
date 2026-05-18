<?php

namespace App\CashFlow\DTO;

/**
 * Represents the t.
 */
final class PeriodResult
{
    public function __construct(
        public readonly int $period,

        public float $constructionScurveWeight = 0.0,
        public float $constructionOutflow      = 0.0,
        public float $capexWeight              = 0.0,
        public float $otherCapex               = 0.0,

        public float $salesWeight  = 0.0,
        public float $salesInflow  = 0.0,
        public float $salesCost    = 0.0,

        public int $operationFlag      = 0,
        public int $operationYearIndex = 0,
        public float $baseRentalIncome = 0.0,
        public float $rentalIncome     = 0.0,

        public float $propertyManagementCost = 0.0,
        public float $insuranceCost          = 0.0,
        public float $maintenanceCost        = 0.0,
        public float $utilitiesCost          = 0.0,
        public float $marketingCost          = 0.0,
        public float $staffingCost           = 0.0,
        public float $opex                   = 0.0,

        public float $noi           = 0.0,
        public float $terminalValue = 0.0,

        public float $projectCashFlow = 0.0,
    ) {}
}