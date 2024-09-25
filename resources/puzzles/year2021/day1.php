<?php

function readInput(array $strList): array {
    $result = [];

    foreach($strList as $line) {
        $result[] = (int) $line;
    }

    return $result;
}

function countIfNextBigger(array $arr): int {
    $result = 0;
    $lastItem = null;

    foreach($arr as $item) {
        if (!is_null($lastItem) && $item > $lastItem) {
            ++$result;
        }

        $lastItem = $item;
    }

    return $result;
}

function countIfBiggerAverage(array $arr): int {
    $sumArray = []; // if sum or average does not matter for comparison, but sum needs less operations

    for($i = 0; $i < (sizeof($arr) - 2); ++$i) {
        $sumArray[] = array_sum(array_slice($arr, $i, 3));
    }

    return countIfNextBigger($sumArray);
}



$testInput = readInput([
    "199",
    "200",
    "208",
    "210",
    "200",
    "207",
    "240",
    "269",
    "260",
    "263"
]);
$tests[] = ['result' => countIfNextBigger($testInput), 'expected' => 7];
$tests[] = ['result' => countIfBiggerAverage($testInput), 'expected' => 5];



$input = readInput(explode("\r\n", $puzzle->input));
$parts[] = ['result' => countIfNextBigger($input), 'expected' => (int)$puzzle->part1];// 1759
$parts[] = ['result' => countIfBiggerAverage($input), 'expected' => (int)$puzzle->part2];// 1805