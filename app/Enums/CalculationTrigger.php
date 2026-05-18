<?php

namespace App\Enums;

enum CalculationTrigger: string
{
    case Initialisation = 'initialisation';
    case ScenarioChange = 'scenario_change';
    case Construction   = 'construction';
    case SCurve         = 's_curve';
    case Sales          = 'sales';
    case Operation      = 'operation';
    case Opex           = 'opex';
}
