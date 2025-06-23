<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoadmapController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('/roadmap', 'Roadmaps')->name('roadmap');
    Route::get('/roadmap/{roadmap_id}', function($roadmap_id) {
        return Inertia::render('Roadmap', [
            'roadmap_id' => $roadmap_id
        ]);
    });
    Route::post('/liked', [RoadmapController::class, 'liked'])->name('liked.roadmap');
    Route::post('/addComment', [RoadmapController::class, 'addComment']);
    Route::post('/addReply', [RoadmapController::class, 'addReply']);
    Route::post('/editComment', [RoadmapController::class, 'editComment']);
    Route::post('/deleteComment', [RoadmapController::class, 'deleteComment']);
    Route::post('/editReply', [RoadmapController::class, 'editReply']);
    Route::post('/deleteReply', [RoadmapController::class, 'deleteReply']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
