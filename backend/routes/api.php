<?php

use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\SoccerController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\WeekController;
use Illuminate\Support\Facades\Route;

Route::apiResource('teams', TeamController::class);
Route::apiResource('soccer', SoccerController::class);
Route::apiResource('leagues', LeagueController::class);
Route::apiResource('weeks', WeekController::class);

Route::post('leagues/{league}/simulate', [LeagueController::class, 'simulate']);
//Route::put('/match/{id}', [LeagueController::class, 'editResult']);
