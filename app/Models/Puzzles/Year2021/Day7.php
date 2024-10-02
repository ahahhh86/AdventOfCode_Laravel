<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class CrabSubmarines {
    private $subPositions = [];



    public function __construct(string $str) {
        $this->subPositions = array_map(
            fn($i): int => (int) $i,
            explode(",", $str)
        );
    }

    public function accumulateFuelAt(int $position, callable $callback): int {
        return array_reduce(
            $this->subPositions,
            fn($carry, $subPos): int => $carry + $callback($subPos, $position)
        );
    }

    public function getMinFuel(callable $callback): int {
        $result = PHP_INT_MAX;
        $loopStart = min($this->subPositions);
        $loopEnd = max($this->subPositions);

        for ($i = $loopStart; $i <= $loopEnd; ++$i) {
            $result = min($result, $this->accumulateFuelAt($i, $callback));
        }

        return $result;
    }



    public static function calculateFuelPart1(int $a, int $b): int {
        return abs($a - $b);
    }

    public static function calculateFuelPart2(int $a, int $b): int {
        $n = abs($a - $b);
        return $n * ($n + 1) / 2; // = Σ(1 + ... + $n)
    }
}



class Day7 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $calculateFuelPart1 = fn($a, $b): int => CrabSubmarines::calculateFuelPart1($a, $b);
        $calculateFuelPart2 = fn($a, $b): int => CrabSubmarines::calculateFuelPart2($a, $b);



        $testSubs = new CrabSubmarines('16,1,2,0,4,2,7,1,2,14');
        $this->addTest($testSubs->accumulateFuelAt(1, $calculateFuelPart1), 41);
        $this->addTest($testSubs->accumulateFuelAt(3, $calculateFuelPart1), 39);
        $this->addTest($testSubs->accumulateFuelAt(10,$calculateFuelPart1), 71);
        $this->addTest($testSubs->accumulateFuelAt(2, $calculateFuelPart1), 37);
        $this->addTest($testSubs->getMinFuel($calculateFuelPart1), 37);

        $this->addTest($testSubs->accumulateFuelAt(2, $calculateFuelPart2), 206);
        $this->addTest($testSubs->accumulateFuelAt(5, $calculateFuelPart2), 168);
        $this->addTest($testSubs->getMinFuel($calculateFuelPart2), 168);



        $Subs = new CrabSubmarines($puzzle->input);
        $this->addResult($Subs->getMinFuel($calculateFuelPart1), (int)$puzzle->part1); // 326132
        $this->addResult($Subs->getMinFuel($calculateFuelPart2), (int)$puzzle->part2); // 88612508
    }
}