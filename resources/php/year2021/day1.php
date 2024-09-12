<?php

function countDeeper(array $stringList): int {
    $deeper = 0;
    $lastLine = null;

    foreach($stringList as $line) {
        if ($lastLine === null) {
            $lastLine = $line;
            continue;
        }

        if ((int)$line > (int)$lastLine) {
            ++$deeper;
        }

        $lastLine = $line;
    }

    return $deeper;
}

function countDeeperAverage(array $stringList): int {
    $deeper = 0;
    $loopEnd = sizeof($stringList);

    for($i=3; $i < $loopEnd; $i++) {
        $sum1 = $stringList[$i-3] + $stringList[$i-2] + $stringList[$i-1];
        $sum2 = $stringList[$i-2] + $stringList[$i-1] + $stringList[$i];

        if ($sum1 < $sum2) {
            ++$deeper;
        }
    }

    return $deeper;
}



$testInput = ['199','200','208','210','200','207','240','269','260','263',];
$tests = [];
$tests[] = ['result' => countDeeper($testInput), 'expected' => 7];
$tests[] = ['result' => countDeeperAverage($testInput), 'expected' => 5];



$parts = [];
$input = explode("\r\n", $puzzle->input);
$parts[] = ['result' => countDeeper($input), 'expected' => (int)$puzzle->part1];// 1759
$parts[] = ['result' => countDeeperAverage($input), 'expected' => (int)$puzzle->part2];//1805