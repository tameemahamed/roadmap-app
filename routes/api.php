<?php

use App\Http\Controllers\RoadmapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/roadmaps/{status_ids}/{filter_upvotes}', [RoadmapController::class, 'filteredRoadmaps']);
    Route::get('/roadmap/{roadmap_id}', [RoadmapController::class, 'selectedRoadmap']);
    
});
// uncomment for testing purposes
// Route::get('/roadmaps/{status_ids}/{filter_upvotes}', [RoadmapController::class, 'filteredRoadmaps']);
// Route::get('/roadmap/{roadmap_id}', [RoadmapController::class, 'selectedRoadmap']);

// Route::get('/roadmaps', [RoadmapController::class, 'AllRoadmaps']);
// Route::get('/status/{status_id}', [RoadmapController::class, 'GetStatus']);
// Route::get('/statuses', [RoadmapController::class, 'AllStatuses']);
