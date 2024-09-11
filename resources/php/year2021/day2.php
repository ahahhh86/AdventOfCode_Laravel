<?php

function navigate($stringList) {
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

function navigate2($stringList) {
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

$input = explode("\r\n", $input);
$parts = [];
$parts[] = ['result' => navigate($input), 'expected' => 1882980];
$parts[] = ['result' => navigate2($input), 'expected' => 1971232560];