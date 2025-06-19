<?php

use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\SoccerController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\WeekController;
use Illuminate\Support\Facades\Route;

Route::post('leagues/{league}/simulate', [LeagueController::class, 'simulate']);
Route::post('soccers/{league}/simulate-all-unplayed', [SoccerController::class, 'simulateAllUnPlayed']);
Route::post('weeks/{week}/simulate-week', [WeekController::class, 'simulateWeek']);
Route::get('leagues/{league}/weeks/current/matches', [WeekController::class, 'currentMatchesByLeague']);

Route::apiResource('teams', TeamController::class);
Route::apiResource('soccers', SoccerController::class);
Route::apiResource('leagues', LeagueController::class);
Route::apiResource('weeks', WeekController::class);
