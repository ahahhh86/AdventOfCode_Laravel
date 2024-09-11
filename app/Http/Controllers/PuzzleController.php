<?php

namespace App\Http\Controllers;

use App\Models\Puzzle;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Nullable;

class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $puzzles = Puzzle::all()->sortBy('day');

        return view('puzzles.index', ['puzzles' => $puzzles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('puzzles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // request()->validate([
        //     'title' => ['required', 'min:3'],
        //     'salary' => ['required']
        // ]);
        
        Puzzle::create([
            'year' => request('year'),
            'day' => request('day'),
            'input' => request('input'),
        ]);

        return redirect('/puzzles');
    }

    /**
     * Display the specified resource.
     */
    public function show(Puzzle $puzzle)
    {
        return view('puzzles.show', ['puzzle' => $puzzle]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puzzle $puzzle)
    {
        dd("edit", $puzzle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Puzzle $puzzle)
    {
        dd("update", $puzzle, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Puzzle $puzzle)
    // {
    //     //
    // }
}
