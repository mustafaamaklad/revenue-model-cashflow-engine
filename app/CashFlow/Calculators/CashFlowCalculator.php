<?php

namespace App\CashFlow\Calculators;

use App\CashFlow\DTO\PeriodResult;

/**
 * ProjectCashFlow_t = SalesInflow_t +
 *  RentalIncome_t +
 *  TerminalValue_t -
 *  ConstructionOutflow_t -
 *  OtherCapEx_t -
 *  OPEX_t -
 *  SalesCost_t
 *
 * Last Pipe
 */
final class CashFlowCalculator
{
    public function calculate(PeriodResult $period): void
    {
        $period->projectCashFlow =
            $period->salesInflow
            + $period->rentalIncome
            + $period->terminalValue
            - $period->constructionOutflow
            - $period->otherCapex
            - $period->opex
            - $period->salesCost;
    }
}
