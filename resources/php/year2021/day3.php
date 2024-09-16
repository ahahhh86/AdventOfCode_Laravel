<?php

function readInput(array $strList): array {
    $matrix = [];
    foreach($strList as $line) {
        $matrix[] = str_split($line, 1);
    }

    return $matrix;
}

function getMostCommonBitAt(int $position, array $matrix): string {
    $count0 = 0;
    $count1 = 0;

    foreach($matrix as $line) {
        switch ($line[$position]) {
            case "0":
                ++$count0;
                break;
            
            case "1":
                ++$count1;
                break;

            default:
                throw new ErrorException("unexpected input: {$line[$position]}");
        }
    }

    if ($count0 > $count1) {
        return '0';
    } else {
        return '1'; // also if count1 = count0, because the puzzle (part 2) expects this
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
        switch ($chr) {
            case '0':
                $result .= '1';
                break;

            case '1':
                $result .= '0';
                break;

            default:
                throw new ErrorException("unexpected input: {$chr}");
        }
    }

    return $result;
}

function powerConsumption(array $matrix): int {
    $mostcommonBits = implode(getMostCommonBits($matrix));

    $gammaRate = bindec($mostcommonBits);
    $epsilonRate = bindec(invertBits($mostcommonBits));

    return $gammaRate * $epsilonRate;
}

function filterMatrix(array $matrix, int $position, string $filter): array {
    $result = [];

    foreach($matrix as $line) {
        if ($line[$position] === $filter) {
            $result[] = $line;
        }
    }

    return $result;
}

function getRating(array $matrix, $invert): int {
    for ($i = 0; $i < sizeof($matrix[0]); ++$i) {
        $mostCommonBit = $invert(getMostCommonBitAt($i, $matrix));

        $matrix = filterMatrix($matrix, $i, $mostCommonBit);

        if (sizeof($matrix) === 1) {
            return bindec(implode($matrix[0]));
        }
    }

    throw new ErrorException("theere are no lines left");
}

// does nothing, but needed for $oxygenGeneratorRating / getRating()
function bits(string $str): string {
    return $str;
}

function lifeSupportRating(array $matrix) {
    $oxygenGeneratorRating = getRating($matrix, "bits");
    $co2ScrubberRating = getRating($matrix, "invertBits");

    return $oxygenGeneratorRating * $co2ScrubberRating;
}



$testInput = readInput([
    "00100",
    "11110",
    "10110",
    "10111",
    "10101",
    "01111",
    "00111",
    "11100",
    "10000",
    "11001",
    "00010",
    "01010"
]);
$tests = [];
$tests[] = ['result' => powerConsumption($testInput), 'expected' => 198];
$tests[] = ['result' => lifeSupportRating($testInput), 'expected' => 230];



$input = readInput(explode("\r\n", $puzzle->input));
$parts = [];
$parts[] = ['result' => powerConsumption($input), 'expected' => (int)$puzzle->part1];// 3549854
$parts[] = ['result' => lifeSupportRating($input), 'expected' => (int)$puzzle->part2];// 3765399