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
        // TODO:
        // request()->validate([
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
        if (empty($puzzle->input)) {
            return redirect("/puzzles/{$puzzle->id}/editInput");
        }

        return view('puzzles.show', ['puzzle' => $puzzle]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editInput(Puzzle $puzzle)
    {
        return view('puzzles.editInput', ['puzzle' => $puzzle]);
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
    public function updateInput(Request $request, Puzzle $puzzle)
    {
        // TODO:
        // request()->validate([
        // ]);

        $puzzle->update([
            'input' => request('input'),
        ]);

        return redirect("/puzzles/{$puzzle->id}");
    }
    public function updateParts(Request $request, Puzzle $puzzle)
    {
        // TODO:
        // request()->validate([
        // ]);

        $puzzle->update([
            'part1' => request('part1'),
            'part2' => request('part2'),
        ]);

        return redirect("/puzzles");
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Puzzle $puzzle)
    // {
    //     //
    // }
}
