<?php

use App\Http\Controllers\PuzzleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('puzzles');
});

Route::get('/puzzles', [PuzzleController::class, 'index']);

Route::get('/puzzles/create', [PuzzleController::class, 'create']);
Route::post('/puzzles', [PuzzleController::class, 'store']);

Route::get('/puzzles/{puzzle}', [PuzzleController::class, 'show']);
Route::get('/puzzles/{puzzle}/editInput', [PuzzleController::class, 'editInput']);
Route::patch('/puzzles/{puzzle}/editInput', [PuzzleController::class, 'updateInput']);
Route::patch('/puzzles/{puzzle}', [PuzzleController::class, 'updateParts']);

// Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])
//     ->middleware('auth')
//     ->can('edit', 'job');

// Route::delete('/jobs/{job}', [JobController::class, 'destroy']);
