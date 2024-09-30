<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Fishes {
    private const FISH_CREATION_DURATION = 6;
    //private const NEW_FISH_CREATION_DURATION = 8;
    private const FISH_ARRAY_LENGTH = 9;

    private $fishes;
    private $cycleIndex = 0;



    // Instead of tracking each fish separatly, group fishes by days left to reproduce
    // This changes he problem from exponential to linear
    public function __construct(string $str) {
        $this->fishes = array_fill(0, $this::FISH_ARRAY_LENGTH, 0);
        $fishesTimer = explode(',', $str);
        array_walk($fishesTimer, function($timer): void {
            ++$this->fishes[(int)$timer];
        });
    }
    
    private function growOnce(): void {
        $newFishes = array_shift($this->fishes);
        $this->fishes[$this::FISH_CREATION_DURATION] += $newFishes; // add parents with 6 days left
        $this->fishes[] = $newFishes;                               // add new children with 8 days left
    }

    public function calculateGrowth(int $cycles): int {
        for (; $this->cycleIndex < $cycles; ++$this->cycleIndex) {
            $this->growOnce();
        }

        return array_sum($this->fishes);
    }
}

class Day6 extends Day0 {
    private const PART1_CYCLES = 80;
    private const PART2_CYCLES = 256;

    public function __construct(Puzzle $puzzle) {
        $testFishes = new Fishes('3,4,3,1,2');
        $this->addTest($testFishes->calculateGrowth(18), 26);
        $this->addTest($testFishes->calculateGrowth($this::PART1_CYCLES), 5934);
        $this->addTest($testFishes->calculateGrowth($this::PART2_CYCLES), 26984457539);



        $fishes = new Fishes($puzzle->input);
        $this->addResult($fishes->calculateGrowth($this::PART1_CYCLES), (int)$puzzle->part1); // 390923
        $this->addResult($fishes->calculateGrowth($this::PART2_CYCLES), (int)$puzzle->part2); // 1749945484935
    }
}