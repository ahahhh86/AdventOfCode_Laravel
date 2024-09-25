<?php

namespace App\Models\Puzzles;

use App\Models\Puzzle;

class Day {
    public $tests = [];
    public $results = [];



    public function __construct(Puzzle $puzzle) {
        $dayClass = "App\Models\Puzzles\Year{$puzzle->year}\Day{$puzzle->day}";
        if (class_exists($dayClass)) {
            $day = new $dayClass($puzzle);
            $this->tests = $day->getTests();
            $this->results = $day->getResults();
        }
    }



    public function hasTests(): bool {
        return !empty($this->tests);
    }

    public function hasResults(): bool {
        return !empty($this->results);
    }

    public function isSolved(): bool {
        return $this->hasTests() || $this->hasResults();
    }



    public function getTests(): array {
        return $this->tests;
    }

    public function getResults(): array {
        return $this->results;
    }
}