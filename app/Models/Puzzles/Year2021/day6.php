<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day6 extends Day0 {
    private const PART1_CYCLES = 80;
    private const PART2_CYCLES = 256;

    private const FISH_CREATION_DURATION = 6;
    //private const NEW_FISH_CREATION_DURATION = 8;
    private const FISH_ARRAY_LENGTH = 9;



    // Instead of tracking each fish separatly, group fishes by days left to reproduce
    // This changes he problem from exponential to linear
    function readInput(string $str): array {
        $fishes = explode(',', $str);
        $result = array_fill(0, $this::FISH_ARRAY_LENGTH, 0);

        foreach($fishes as $fish) {
            ++$result[(int)$fish];
        }

        return $result;
    }
    
    function growOnce(array &$fishes): void {
        $newFishes = array_shift($fishes);
        $fishes[] = $newFishes;     // add new children with 8 days left
        $fishes[$this::FISH_CREATION_DURATION] += $newFishes;   // add parents with 6 days left
    }

    function calculateGrowth(array $fishes, int $cycles): int {
        for ($i = 0; $i < $cycles; ++$i) {
            $this->growOnce($fishes);
        }

        return array_sum($fishes);
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput('3,4,3,1,2');
        $this->addTest($this->calculateGrowth($testInput, 18), 26);
        $this->addTest($this->calculateGrowth($testInput, $this::PART1_CYCLES), 5934);
        $this->addTest($this->calculateGrowth($testInput, $this::PART2_CYCLES), 26984457539);



        $input = $this->readInput($puzzle->input);
        $this->addResult($this->calculateGrowth($input, $this::PART1_CYCLES), (int)$puzzle->part1); // 390923
        $this->addResult($this->calculateGrowth($input, $this::PART2_CYCLES), (int)$puzzle->part2); // 1749945484935
    }
}