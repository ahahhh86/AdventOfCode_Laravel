<?php

function navigate(array $stringList): int {
    $horizontalPos = 0;
    $depth = 0;

    foreach($stringList as $line) {
        $input = explode(' ', $line);
        if ($input[0] === 'forward') {
            $horizontalPos += $input[1];
        } elseif ($input[0] === 'down') {
            $depth += $input[1];
        } elseif ($input[0] === 'up') {
            $depth -= $input[1];
        } else {
            throw new ErrorException("unexpected input: $line");
        }
    }

    return $horizontalPos * $depth;
}

function navigate2(array $stringList): int {
    $horizontalPos = 0;
    $depth = 0;
    $aim = 0;

    foreach($stringList as $line) {
        $input = explode(' ', $line);
        if ($input[0] === 'forward') {
            $horizontalPos += $input[1] *$aim;
            $depth += $input[1];
        } elseif ($input[0] === 'down') {
            $aim += $input[1];
        } elseif ($input[0] === 'up') {
            $aim -= $input[1];
        } else {
            throw new ErrorException("unexpected input: $line");
        }
    }

    return $horizontalPos * $depth;
}



$testInput = ['forward 5', 'down 5', 'forward 8', 'up 3', 'down 8', 'forward 2',];
$tests = [];
$tests[] = ['result' => navigate($testInput), 'expected' => 150];
$tests[] = ['result' => navigate2($testInput), 'expected' => 900];



$parts = [];
$input = explode("\r\n", $puzzle->input);
$parts[] = ['result' => navigate($input), 'expected' => (int)$puzzle->part1];//1882980
$parts[] = ['result' => navigate2($input), 'expected' => (int)$puzzle->part2];//1971232560