<?php

function readInput(array $strList): array {
    $result = [];

    foreach($strList as $str) {
        $item = explode(" ", $str);
        $result[] = ['direction' => $item[0], 'value' => (int)$item[1]];
    }

    return $result;
}

function navigate(array $instructions): int {
    $horizontalPos = 0;
    $depth = 0;

    foreach($instructions as $instruction) {
        switch ($instruction['direction']) {
            case 'forward':
                $horizontalPos += $instruction['value'];
                break;

            case 'down':
                $depth += $instruction['value'];
                break;

            case 'up':
                $depth -= $instruction['value'];
                break;
            
            default:
                throw new ErrorException("unexpected input: {$instruction['direction']}");
        }
    }

    return $horizontalPos * $depth;
} 

function navigate2(array $instructions): int {
    $horizontalPos = 0;
    $depth = 0;
    $aim = 0;

    foreach($instructions as $instruction) {
        switch ($instruction['direction']) {
            case 'forward':
                $horizontalPos += $instruction['value'] * $aim;
                $depth += $instruction['value'];
                break;

            case 'down':
                $aim += $instruction['value'];
                break;

            case 'up':
                $aim -= $instruction['value'];
                break;
            
            default:
                throw new ErrorException("unexpected input: {$instruction['direction']}");
        }
    }

    return $horizontalPos * $depth;
} 



$testInput = readInput([
    "forward 5",
    "down 5",
    "forward 8",
    "up 3",
    "down 8",
    "forward 2"
]);
$tests = [];
$tests[] = ['result' => navigate($testInput), 'expected' => 150];
$tests[] = ['result' => navigate2($testInput), 'expected' => 900];



$input = readInput(explode("\r\n", $puzzle->input));
$parts = [];
$parts[] = ['result' => navigate($input), 'expected' => (int)$puzzle->part1];// 1882980
$parts[] = ['result' => navigate2($input), 'expected' => (int)$puzzle->part2];// 1971232560