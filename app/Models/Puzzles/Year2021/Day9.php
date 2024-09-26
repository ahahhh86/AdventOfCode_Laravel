<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day9 extends Day0 {
    private function readInput(array $arr): array {
        $result = [];

        foreach($arr as $line) {
            $buffer = str_split($line, 1);
            $tmp = [];
            foreach($buffer as $item) {// TODO: simpler?
                $tmp[] = (int)$item; 
            }
            $result[] = $tmp;
        }

        return $result;
    }

    private function checkMin(array $map, array $position): bool {
        $heights = [];
        for($i = -1; $i <= 1; ++$i) {
            for($j = -1; $j <= 1; ++$j) {
                $heights[] = $map[$position[0] + $i][$position[1] + $j] ?? PHP_INT_MAX;
            }
        }
        $min = min($heights);

        return $map[$position[0]][$position[1]] === $min;
    }

    private function calculateRisks(array $map): int {
        $risks = [];

        for($i = 0; $i < sizeof($map); ++$i) {
            for($j = 0; $j < sizeof($map[$i]); ++$j) {
                if ($this->checkMin($map, [$i, $j])) {
                    $risks[] = $map[$i][$j] + 1;
                }
            }
        }

        return array_sum($risks);
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
            '2199943210',
            '3987894921',
            '9856789892',
            '8767896789',
            '9899965678',
        ]);
        $this->addTest($this->calculateRisks($testInput), 15);
        // $this->addTest($this->calculateGrowth($testInput, $this::PART1_CYCLES), 5934);
        // $this->addTest($this->calculateGrowth($testInput, $this::PART2_CYCLES), 26984457539);



        $input = $this->readInput(explode(PHP_EOL, $puzzle->input));
        $this->addResult($this->calculateRisks($input), (int)$puzzle->part1); // 502
        // $this->addResult($this->calculateGrowth($input, $this::PART2_CYCLES), (int)$puzzle->part2); // 
    }
}