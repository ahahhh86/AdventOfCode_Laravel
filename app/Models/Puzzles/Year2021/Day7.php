<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class CrabSubmarines {
    private $subPosition = [];

    public function __construct(string $str) {
        $arr = explode(",", $str);
        $this->subPosition = array_map(fn($i): int => (int)$i, $arr);
    }

    public function accumulateFuelAt(int $position, $fn): int {
        return array_reduce($this->subPosition, fn($acc, $item): int => $acc + $fn($item, $position));
    }

    public function getMinFuel($fn): int {
        $result = PHP_INT_MAX;
        $max = max($this->subPosition);

        for ($i = min($this->subPosition); $i <= $max; ++$i) {
            $result = min($result, $this->accumulateFuelAt($i, $fn));
        }

        return $result;
    }

    public static function calculateFuelPart1(int $a, int $b): int {
        return abs($a - $b);
    }

    public static function calculateFuelPart2(int $a, int $b): int {
        $n = abs($a - $b);
        return (int) ($n*($n+1)/2);
    }
}

class Day7 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $calculateFuelPart1 = fn($a, $b): int => CrabSubmarines::calculateFuelPart1($a, $b);
        $calculateFuelPart2 = fn($a, $b): int => CrabSubmarines::calculateFuelPart2($a, $b);



        $testSubs = new CrabSubmarines('16,1,2,0,4,2,7,1,2,14');
        $this->addTest($testSubs->accumulateFuelAt(2, $calculateFuelPart1), 37);
        $this->addTest($testSubs->accumulateFuelAt(1, $calculateFuelPart1), 41);
        $this->addTest($testSubs->accumulateFuelAt(3, $calculateFuelPart1), 39);
        $this->addTest($testSubs->accumulateFuelAt(10,$calculateFuelPart1), 71);
        $this->addTest($testSubs->getMinFuel($calculateFuelPart1), 37);

        $this->addTest($testSubs->accumulateFuelAt(5, $calculateFuelPart2), 168);
        $this->addTest($testSubs->accumulateFuelAt(2, $calculateFuelPart2), 206);
        $this->addTest($testSubs->getMinFuel($calculateFuelPart2), 168);



        $Subs = new CrabSubmarines($puzzle->input);
        $this->addResult($Subs->getMinFuel($calculateFuelPart1), (int)$puzzle->part1); // 326132
        $this->addResult($Subs->getMinFuel($calculateFuelPart2), (int)$puzzle->part2); // 88612508
    }
}