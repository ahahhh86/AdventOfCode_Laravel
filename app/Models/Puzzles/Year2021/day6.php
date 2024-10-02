<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Fishes {
    private const FISH_CREATION_DURATION = 6;
    //private const NEW_FISH_CREATION_DURATION = 8; // FISH_CREATION_DURATION + 2
    private const FISH_ARRAY_LENGTH = 9; // NEW_FISH_CREATION_DURATION + 1

    private $fishes;
    private $cycleIndex = 0;



    // Instead of tracking each fish separatly, group fishes by days left to reproduce
    // This changes the problem from exponential to linear
    public function __construct(string $str) {
        $this->fishes = array_fill(0, $this::FISH_ARRAY_LENGTH, 0);
        $fishesTimer = explode(',', $str);
        array_walk(
            $fishesTimer,
            fn($timer) => ++$this->fishes[$timer]
        );
    }

    public function grow(int $cycles): int {
        for (; $this->cycleIndex < $cycles; ++$this->cycleIndex) {
            $this->growOnce();
        }

        return array_sum($this->fishes);
    }



    private function growOnce(): void {
        $newFishes = array_shift($this->fishes);
        $this->fishes[] = $newFishes;                               // add new children with 8 days left
        $this->fishes[$this::FISH_CREATION_DURATION] += $newFishes; // add parents with 6 days left
    }
}



class Day6 extends Day0 {
    private const PART1_CYCLES = 80;
    private const PART2_CYCLES = 256;

    public function __construct(Puzzle $puzzle) {
        $testFishes = new Fishes('3,4,3,1,2');
        $this->addTest($testFishes->grow(18), 26);
        $this->addTest($testFishes->grow($this::PART1_CYCLES), 5934);
        $this->addTest($testFishes->grow($this::PART2_CYCLES), 26984457539);



        $fishes = new Fishes($puzzle->input);
        $this->addResult($fishes->grow($this::PART1_CYCLES), (int)$puzzle->part1); // 390923
        $this->addResult($fishes->grow($this::PART2_CYCLES), (int)$puzzle->part2); // 1749945484935
    }
}