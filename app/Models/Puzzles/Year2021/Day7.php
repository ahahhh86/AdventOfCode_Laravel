<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day7 extends Day0 {
    private function readInput(string $str): array {
        $result = [];
        $arr = explode(",", $str);

        foreach($arr as $item) {
            $result[] = (int) $item;
        }

        return $result;
    }

    private function accumulateFuelAt(Array $subs, int $position, callable $fn): int {
        $result = 0;
    
        foreach($subs as $sub) {
            $result += $fn($sub, $position);
        }
    
        return $result;
    }

    private function getMinFuel(Array $subs, callable $fn): int {
        $result = PHP_INT_MAX;
        $max = max($subs);

        for ($i = min($subs); $i <= $max; ++$i) {
            $result = min($result, $this->accumulateFuelAt($subs, $i, $fn));
        }

        return $result;
    }

    private function calculateFuelPart1(int $a, int $b): int {
        return abs($a - $b);
    }

    private function calculateFuelPart2(int $a, int $b): int {
        $n = abs($a - $b);
        return (int) ($n*($n+1)/2);
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput('16,1,2,0,4,2,7,1,2,14');
        $this->addTest($this->accumulateFuelAt($testInput, 1, [$this, 'calculateFuelPart1']), 41);
        $this->addTest($this->accumulateFuelAt($testInput, 2, [$this, 'calculateFuelPart1']), 37);
        $this->addTest($this->accumulateFuelAt($testInput, 3, [$this, 'calculateFuelPart1']), 39);
        $this->addTest($this->accumulateFuelAt($testInput, 10,[$this, 'calculateFuelPart1']), 71);
        $this->addTest($this->getMinFuel($testInput, [$this, 'calculateFuelPart1']), 37);

        $this->addTest($this->accumulateFuelAt($testInput, 2, [$this, 'calculateFuelPart2']), 206);
        $this->addTest($this->accumulateFuelAt($testInput, 5, [$this, 'calculateFuelPart2']), 168);
        $this->addTest($this->getMinFuel($testInput, [$this, 'calculateFuelPart2']), 168);



        $input = $this->readInput($puzzle->input);
        $this->addResult($this->getMinFuel($input, [$this, 'calculateFuelPart1']), (int)$puzzle->part1); // 326132
        $this->addResult($this->getMinFuel($input, [$this, 'calculateFuelPart2']), (int)$puzzle->part2); // 88612508
    }
}