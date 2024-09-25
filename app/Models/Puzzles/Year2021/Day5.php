<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;

class Day5 extends Day0 {
    private function readInput(array $strList): array {
        $result = [];
    
        foreach($strList as $line) {
            $buffer = preg_split("/[\s,]+/", $line);
            $result[] = ['from' => [(int)$buffer[0], (int)$buffer[1]], 'to' => [(int)$buffer[3], (int)$buffer[4]]];
        }
    
        return $result;
    }
    
    private const GRID_SIZE = 1000;
    private function createGrid(): array {
        $result = [];
    
        for($i=0; $i < $this::GRID_SIZE; ++$i) {
            $result[] = array_fill(0, $this::GRID_SIZE, 0);
        }
    
        return $result;
    }
    
    private function ventsToArray(array $vents, bool $diagonal = false): array {
        $result = [];
    
        $xStep = $vents['to'][0] <=> $vents['from'][0];
        $yStep = $vents['to'][1] <=> $vents['from'][1];
        
        if ($yStep === 0) {
            // no changes in y-axis, so use x for steps
            $countSteps = abs($vents['to'][0] - $vents['from'][0]);
    
        } elseif (!$diagonal && $xStep !== 0) {
            // changes in both axis, but we should ignore diagonals in part 1
            return [];
    
        } else {
            // use y for steps
            $countSteps = abs($vents['to'][1] - $vents['from'][1]);
        }
        
        for($i = 0; $i <= $countSteps; ++$i) {
            $xPos = $vents['from'][0] + $xStep * $i;
            $yPos = $vents['from'][1] + $yStep * $i;
            $result[] = [$yPos, $xPos];
        }
    
        return $result;
    }
    
    private function addVents(array &$grid, array $vents): void {
        foreach($vents as $vent) {
            ++$grid[$vent[1]][$vent[0]];
        }
    }
    
    private function countCrossings(array $grid): int{
        $result = 0;
    
        foreach($grid as $line) {
            foreach($line as $item) {
                if ($item > 1) {
                    ++$result;
                }
            }
        }
    
        return $result;
    }
    
    
    private function findSafeSpaces(array $ventList, bool $diagonal = false): int {
        $grid = $this->createGrid();
    
        foreach($ventList as $vents) {
            $this->addVents($grid, $this->ventsToArray($vents, $diagonal));
        }
    
    
        return $this->countCrossings($grid);
    }


    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
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
        $this->addTest($this->findSafeSpaces($testInput), 5);
        $this->addTest($this->findSafeSpaces($testInput, true), 12);
        
        
        
        $input = $this->readInput(explode("\r\n", $puzzle->input));
        $this->addResult($this->findSafeSpaces($input), (int)$puzzle->part1); // 8622
        $this->addResult($this->findSafeSpaces($input, true), (int)$puzzle->part2); // 22037
    }
}