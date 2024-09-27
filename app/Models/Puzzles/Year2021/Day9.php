<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Position {
    public $x = 0;
    public $y = 0;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString(): string
    {
        return "$this->x : $this->y";
    }
}

class Map {
    private $map = [];
    private $lowPoints = [];
    private const MAX_HEIGHT = 9;

    public function __construct(array $arr) {
        foreach($arr as $line) {
            $buffer = [];
            foreach(str_split($line, 1) as $item) {
                $buffer[] = (int)$item; 
            }
            $this->map[] = $buffer;
        }
    }

    public function calculateRisks(): int {
        $risks = [];

        for($i = 0; $i < sizeof($this->map); ++$i) {
            for($j = 0; $j < sizeof($this->map[$i]); ++$j) {
                if ($this->checkMin(new Position($i, $j))) {
                    $this->lowPoints[] = new Position($i, $j);
                    $risks[] = $this->map[$i][$j] + 1;
                }
            }
        }

        return array_sum($risks);
    }

    public function multiplyBasins(): int {
        $basins = $this->get3BiggestBasins();
        return $basins[0] * $basins[1] * $basins[2];
    }

    private function checkMin(Position $position): bool {
        $heights = [];
        for($i = -1; $i <= 1; ++$i) {
            for($j = -1; $j <= 1; ++$j) {
                $heights[] = $this->map[$position->x + $i][$position->y + $j] ?? PHP_INT_MAX;
            }
        }
        $min = min($heights);

        return $this->map[$position->x][$position->y] === $min;
    }

    private function getBasinPositions(Position $pos): array {
        $directions = [[-1, 0], [+1, 0], [0, -1], [0, +1]];
        $height = $this->map[$pos->x][$pos->y];

        $result = [$pos];

        foreach($directions as $direction) {
            $adjacentPos = new Position(
                $pos->x + $direction[0],
                $pos->y + $direction[1]
            );
            $adjacentHeight = $this->map[$adjacentPos->x][$adjacentPos->y] ?? $this::MAX_HEIGHT;

            if ($adjacentHeight > $height && $adjacentHeight < $this::MAX_HEIGHT) {
                $result = array_merge($result, $this->getBasinPositions($adjacentPos));
            }
        }

        return $result;
    }

    private function getBasinSize(Position $position): int {
        return sizeof(array_unique($this->getBasinPositions($position)));
    }

    private function getBasinSizes(): array {
        $result = [];

        foreach($this->lowPoints as $lowPoint) {
            $size = $this->getBasinSize($lowPoint);
            $result[] = $size;
        }

        return $result;
    }

    private function get3BiggestBasins(): array {
        $basins = $this->getBasinSizes();
        rsort($basins);
        return [$basins[0], $basins[1], $basins[2]];
    }
}

class Day9 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $map = new Map([
            '2199943210',
            '3987894921',
            '9856789892',
            '8767896789',
            '9899965678',
        ]);
        $this->addTest($map->calculateRisks(), 15);
        $this->addTest($map->multiplyBasins(), 1134);



        $map = new Map(explode(PHP_EOL, $puzzle->input));
        $this->addResult($map->calculateRisks(), (int)$puzzle->part1); // 502
        $this->addResult($map->multiplyBasins(), (int)$puzzle->part2); // 1330560
    }
}