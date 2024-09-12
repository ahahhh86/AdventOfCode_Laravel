<?php

function createMatrix(array $stringList): array  {
    $matrix = [];
    foreach($stringList as $line) {
        $matrix[] = str_split($line, 1);
    }

    return $matrix;
}

function getMostCommonBitAt(int $position, array $matrix): string {
    $count0 = 0;
    $count1 = 0;

    for($i=0; $i<sizeof($matrix); ++$i) {
        if ($matrix[$i][$position]==="0") {
            ++$count0;
        } elseif ($matrix[$i][$position]==="1") {
            ++$count1;
        } else {
            throw new ErrorException("unexpected input: $matrix[j][i]");
        }
    }

    if ($count0 === $count1) {
        return '1';
    } elseif ($count0 > $count1) {
        return '0';
    } else {
        return '1';
    }
}

function getMostCommonBits(array $matrix): array {
    $mostCommonBits= [];

    for($i=0; $i<sizeof($matrix[0]); ++$i) {
        $mostCommonBits[]= getMostCommonBitAt($i, $matrix);
    }

    return $mostCommonBits;
}

function invertBits(string $str): string {
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

function powerConsumption(array $stringList): int {
    $matrix = createMatrix($stringList);
    $mostcommonBits = implode(getMostCommonBits($matrix));
    $gammaRate = bindec($mostcommonBits);
    $epsilonRate = bindec(invertBits($mostcommonBits));

    return $gammaRate * $epsilonRate;
}

function reduceMatrix(array $matrix, int $position, string $filter): array {
    $result = [];

    foreach($matrix as $line) {
        if ($line[$position] === $filter) {
            $result[] = $line;
        }
    }

    return $result;
}

function getOxygenGeneratorRating(array $matrix): int {
    for ($i = 0; $i < sizeof($matrix[0]); ++$i) {
        $mostCommonBit = getMostCommonBitAt($i, $matrix);

        $matrix = reduceMatrix($matrix, $i, $mostCommonBit);
        if (sizeof($matrix) === 1) {
            return bindec(implode($matrix[0]));
        }
    }

    throw new ErrorException("theere are no lines left");
}

function getco2ScrubberRating(array $matrix): int {
    for ($i = 0; $i < sizeof($matrix[0]); ++$i) {
        $leastCommonBit = invertBits(getMostCommonBitAt($i, $matrix));

        $matrix = reduceMatrix($matrix, $i, $leastCommonBit);
        if (sizeof($matrix) === 1) {
            return bindec(implode($matrix[0]));
        }
    }

    throw new ErrorException("theere are no lines left");
}

function lifeSupportRating(array $stringList) {
    $matrix = createMatrix($stringList);
    $oxygenGeneratorRating = getOxygenGeneratorRating($matrix);
    $co2ScrubberRating = getco2ScrubberRating($matrix);

    return $oxygenGeneratorRating * $co2ScrubberRating;
}



$testInput = ['00100', '11110', '10110', '10111', '10101', '01111', '00111', '11100', '10000', '11001', '00010', '01010',];
$tests = [];
$tests[] = ['result' => powerConsumption($testInput), 'expected' => 198];
$tests[] = ['result' => lifeSupportRating($testInput), 'expected' => 230];



$parts = [];
$input = explode("\r\n", $puzzle->input);
$parts[] = ['result' => powerConsumption($input), 'expected' => (int)$puzzle->part1];//3549854
$parts[] = ['result' => lifeSupportRating($input), 'expected' => (int)$puzzle->part2];//3765399