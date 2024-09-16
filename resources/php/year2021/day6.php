<?php

// Instead of tracking each fish separatly, group fishes by days left to reproduce
// This changes he problem from exponential to linear
function readInput(string $str): array {
    $fishes = explode(",", $str);
    $result = array_fill(0, 9, 0);

    foreach($fishes as $fish) {
        ++$result[(int)$fish];
    }

    return $result;
}

function growOnce(array &$fishes) {
    $newFishes = array_shift($fishes);
    $fishes[] = $newFishes;     // add new children with 8 days left
    $fishes[6] += $newFishes;   // add parents with 6 days left
}

function calculateGrowth(array $fishes, int $cycles): int {
    for ($i = 0; $i < $cycles; ++$i) {
        growOnce($fishes);
    }

    return array_sum($fishes);
}

const PART1_CYCLES = 80;
const PART2_CYCLES = 256;



$testInput = readInput('3,4,3,1,2');
$tests = [];
$tests[] = ['result' => calculateGrowth($testInput, 18), 'expected' => 26];
$tests[] = ['result' => calculateGrowth($testInput, PART1_CYCLES), 'expected' => 5934];
$tests[] = ['result' => calculateGrowth($testInput, PART2_CYCLES), 'expected' => 26984457539];



$input = readInput($puzzle->input);
$parts = [];
$parts[] = ['result' => calculateGrowth($input, PART1_CYCLES), 'expected' => (int)$puzzle->part1];// 390923
$parts[] = ['result' => calculateGrowth($input, PART2_CYCLES), 'expected' => (int)$puzzle->part2];// 1749945484935