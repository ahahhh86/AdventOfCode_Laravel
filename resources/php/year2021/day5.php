<?php

function readInput(array $strList): array {
    $result = [];

    foreach($strList as $line) {
        $buffer = preg_split("/[\s,]+/", $line);
        $result[] = ['from' => [(int)$buffer[0], (int)$buffer[1]], 'to' => [(int)$buffer[3], (int)$buffer[4]]];
    }

    return $result;
}

const GRID_SIZE = 1000;
function createGrid(): array {
    $result = [];

    for($i=0; $i < GRID_SIZE; ++$i) {
        $result[] = array_fill(0, GRID_SIZE, 0);
    }

    return $result;
}

function ventsToArray(array $vents, bool $diagonal = false): array {
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

function addVents(array &$grid, array $vents): void {
    foreach($vents as $vent) {
        ++$grid[$vent[1]][$vent[0]];
    }
}

function countCrossings(array $grid): int{
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


function findSafeSpaces(array $ventList, bool $diagonal = false): int {
    $grid = createGrid();

    foreach($ventList as $vents) {
        addVents($grid, ventsToArray($vents, $diagonal));
    }


    return countCrossings($grid);
}


$testInput = readInput([
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
$tests = [];
$tests[] = ['result' => findSafeSpaces($testInput), 'expected' => 5];
$tests[] = ['result' => findSafeSpaces($testInput, true), 'expected' => 12];



$input = readInput(explode("\r\n", $puzzle->input));
$parts = [];
$parts[] = ['result' => findSafeSpaces($input), 'expected' => (int)$puzzle->part1];// 8622
$parts[] = ['result' => findSafeSpaces($input, true), 'expected' => (int)$puzzle->part2]; // 22037