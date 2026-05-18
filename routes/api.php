<?php

use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\ConstructionInputController;
use App\Http\Controllers\OperationInputController;
use App\Http\Controllers\OpexInputController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SalesInputController;
use App\Http\Controllers\ScenarioController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects/{project}', [ProjectController::class, 'show']);

    Route::patch('projects/{project}/scenario', [ScenarioController::class, 'update']);

    Route::patch('projects/{project}/inputs/construction', [ConstructionInputController::class, 'update']);
    Route::patch('projects/{project}/inputs/sales', [SalesInputController::class,        'update']);
    Route::patch('projects/{project}/inputs/operation', [OperationInputController::class,    'update']);
    Route::patch('projects/{project}/inputs/opex', [OpexInputController::class,         'update']);

    Route::get('projects/{project}/cash-flow', [CashFlowController::class, 'latest']);
    Route::get('projects/{project}/cash-flow/history', [CashFlowController::class, 'history']);
    Route::get('projects/{project}/cash-flow/{run}', [CashFlowController::class, 'show']);
});
