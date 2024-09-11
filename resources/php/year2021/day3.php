<?php

function createMatrix($stringList) {
    $matrix = [];
    foreach($stringList as $line) {
        $matrix[] = str_split($line, 1);
    }

    return $matrix;
}

function mostCommonBits($stringList) {
    $matrix = createMatrix($stringList);
    $mostCommonBits= [];

    for($i=0; $i<sizeof($matrix[0]); ++$i) {
        $count0 = 0;
        $count1 = 0;

        for($j=0; $j<sizeof($matrix); ++$j) {
            if ($matrix[$j][$i]==="0") {
                ++$count0;
            } elseif ($matrix[$j][$i]==="1") {
                ++$count1;
            } else {
                throw new ErrorException("unexpected input: $matrix[j][i]");
            }
        }

        if ($count0 === $count1) {
            throw new ErrorException("no most common bit: $count0 , $count1");
        } elseif ($count0 > $count1) {
            $mostCommonBits[] = 0;
        } else {
            $mostCommonBits[] = 1;
        }
    }

    return implode($mostCommonBits);
}

function invertBits($str) {
    $result = "";

    foreach (str_split($str, 1) as $chr) {
        if ($chr==='0') {
            $result .= '1';
        } else {
            $result .= '0';
        }
    }

    return $result;
}

function powerConsumption($stringList) {
    $mostcommonBits = mostCommonBits($stringList);
    $gammaRate = bindec($mostcommonBits);
    $epsilonRate = bindec(invertBits($mostcommonBits));

    return $gammaRate * $epsilonRate;
}

function lifeSupportRating($stringList) {
    return $stringList[0];
}

$testInput = ['00100', '11110', '10110', '10111', '10101', '01111', '00111', '11100', '10000', '11001', '00010', '01010',];
$tests = [];
$tests[] = ['result' => powerConsumption($testInput), 'expected' => 198];
$tests[] = ['result' => lifeSupportRating($testInput), 'expected' => 230];

$input = explode("\r\n", $input);
$parts = [];
$parts[] = ['result' => powerConsumption($input), 'expected' => 3549854];
$parts[] = ['result' => lifeSupportRating($input), 'expected' => 0];