<?php

use App\Http\Controllers\PuzzleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('puzzles');
});

Route::get('/puzzles', [PuzzleController::class, 'index']);
Route::get('/puzzles/create/{year?}/{day?}', [PuzzleController::class, 'create']);
Route::post('/puzzles', [PuzzleController::class, 'store']);

// Route::get('/jobs/{job}', [JobController::class, 'show']);

// Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])
//     ->middleware('auth')
//     ->can('edit', 'job');

// Route::patch('/jobs/{job}', [JobController::class, 'update']);
// Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
