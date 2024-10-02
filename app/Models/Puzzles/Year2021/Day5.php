<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;
use App\Models\Puzzles\Position;



class VentLine {
    private $positions = [];
    private $isDiagonal = false;



    public function __construct(string $str) {
        $buffer = preg_split("/[\s,]+/", $str);
        $vents = ['from' => new Position($buffer[0], $buffer[1]), 'to' => new Position($buffer[3], $buffer[4])];

        $xStep = $vents['to']->x <=> $vents['from']->x;
        $yStep = $vents['to']->y <=> $vents['from']->y;

        $axis = ($yStep === 0) ? 'x' : 'y';
        $countSteps = abs($vents['to']->$axis - $vents['from']->$axis); // aka $vent[]->x or $vent[]->y
        if ($yStep !== 0 && $xStep !== 0) {
            $this->isDiagonal = true;
        }

        for($i = 0; $i <= $countSteps; ++$i) {
            $xPos = $vents['from']->x + $xStep * $i;
            $yPos = $vents['from']->y + $yStep * $i;
            $this->positions[] = new Position($yPos, $xPos);
        }
    }

    public function isDiagonal(): bool {
        return $this->isDiagonal;
    }

    public function getPositions(): array {
        return $this->positions;
    }
}



class Map {
    private const MAP_SIZE = 1000;

    private $ventLines;



    public function __construct(array $stringList) {
        $this->ventLines = array_map(
            fn($str): VentLine => new VentLine($str),
            $stringList
        );
    }

    public function findSafeSpaces(bool $diagonal = false): int {
        $map = self::createMap();

        array_walk(
            $this->ventLines,
            function($ventLine) use ($diagonal, &$map): void {
                if ($diagonal || !$ventLine->isDiagonal()) {
                    $this->addVentLine($map, $ventLine);
                }
            }
        );

        return $this->countCrossings($map);
    }



    private static function createMap(): array {
        return array_fill(
            0,
            self::MAP_SIZE,
            array_fill(
                0,
                self::MAP_SIZE,
                0
            )
        );
    }

    private function addVentLine(array &$map, VentLine $ventLine): void {
        $vents = $ventLine->getPositions();
        array_walk(
            $vents,
            function($vent) use (&$map): void {
                ++$map[$vent->x][$vent->y];
            }
        );
    }

    private function countCrossings(array $map): int{
        return count(array_filter(
            array_merge(...$map),
            fn($item): bool => $item > 1
        ));
    }
}

class Day5 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testMap = new Map([
            '0,9 -> 5,9',
            '8,0 -> 0,8',
            '9,4 -> 3,4',
            '2,2 -> 2,1',
            '7,0 -> 7,4',
            '6,4 -> 2,0',
            '0,9 -> 2,9',
            '3,4 -> 1,4',
            '0,0 -> 8,8',
            '5,5 -> 8,2',
        ]);
        $this->addTest($testMap->findSafeSpaces(), 5);
        $this->addTest($testMap->findSafeSpaces(true), 12);



        $map = new Map(explode(PHP_EOL, $puzzle->input));
        $this->addResult($map->findSafeSpaces(), (int)$puzzle->part1); // 8622
        $this->addResult($map->findSafeSpaces(true), (int)$puzzle->part2); // 22037
    }
}