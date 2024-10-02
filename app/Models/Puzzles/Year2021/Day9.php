<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;
use App\Models\Puzzles\Position;



class Map {
    private const LARGES_BASINS_COUNT = 3;
    private const MAX_HEIGHT = 9;

    private $map = [];
    private $lowPoints = [];



    public function __construct(array $heightMap) {
        $this->map = array_map(
            function($stringHeights): array {
                $charHeights = str_split($stringHeights, 1);
                return array_map(
                    fn($height): int => (int)$height,
                    $charHeights
                );
            },
            $heightMap
        );
    }

    public function calculateRisks(): int {
        $risks = [];

        for($i = 0; $i < sizeof($this->map); ++$i) {
            for($j = 0; $j < sizeof($this->map[$i]); ++$j) {
                $newPosition = new Position($i, $j);
                if ($this->checkMin($newPosition)) {
                    $this->lowPoints[] = $newPosition;
                    $risks[] = $this->map[$i][$j] + 1;
                }
            }
        }

        return array_sum($risks);
    }

    public function multiplyBasins(): int {
        return array_product($this->get3BiggestBasins());
    }



    private function checkMin(Position $pos): bool {
        $heights = [];
        for($i = -1; $i <= 1; ++$i) {
            for($j = -1; $j <= 1; ++$j) {
                $heights[] = $this->map[$pos->x + $i][$pos->y + $j] ?? PHP_INT_MAX;
            }
        }

        return $this->map[$pos->x][$pos->y] === min($heights);
    }

    private function getBasinPositions(Position $pos): array {
        $directions = [
            new Position(-1, 0),
            new Position(+1, 0),
            new Position(0, -1),
            new Position(0, +1)
        ];
        $height = $this->map[$pos->x][$pos->y];
        $result = [$pos];

        array_walk(
            $directions,
            function($direction) use($pos, $height, &$result): void {
                $adjacentPos = new Position(
                    $pos->x + $direction->x,
                    $pos->y + $direction->y
                );
                $adjacentHeight = $this->map[$adjacentPos->x][$adjacentPos->y] ?? $this::MAX_HEIGHT;

                if ($adjacentHeight > $height && $adjacentHeight < $this::MAX_HEIGHT) {
                    $result = array_merge($result, $this->getBasinPositions($adjacentPos));
                }
            }
        );

        return $result;
    }

    private function getBasinSize(Position $position): int {
        return sizeof(array_unique($this->getBasinPositions($position)));
    }

    private function getBasinSizes(): array {
        return array_map(
            fn($lowPoint): int => $this->getBasinSize($lowPoint),
            $this->lowPoints
        );
    }

    private function get3BiggestBasins(): array {
        $basins = $this->getBasinSizes();
        rsort($basins);
        return array_splice($basins, 0, self::LARGES_BASINS_COUNT);
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