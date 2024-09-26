<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day3 extends Day0 {
    private function readInput(array $strList): array {
        $matrix = [];
        foreach($strList as $line) {
            $matrix[] = str_split($line, 1);
        }

        return $matrix;
    }

    private function getMostCommonBitAt(int $position, array $matrix): string {
        $count0 = 0;
        $count1 = 0;

        foreach($matrix as $line) {
            switch ($line[$position]) {
                case '0':
                    ++$count0;
                    break;
                
                case '1':
                    ++$count1;
                    break;

                default:
                    throw new \ErrorException("unexpected input: {$line[$position]}");
            }
        }

        if ($count0 > $count1) {
            return '0';
        } else {
            return '1'; // also if count1 == count0, because the puzzle (part 2) expects this
        }

    }

    private function getMostCommonBits(array $matrix): array {
        $mostCommonBits= [];

        for($i=0; $i<sizeof($matrix[0]); ++$i) {
            $mostCommonBits[]= $this->getMostCommonBitAt($i, $matrix);
        }

        return $mostCommonBits;
    }

    private function invertBits(string $str): string {
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
                    throw new \ErrorException("unexpected input: {$chr}");
            }
        }

        return $result;
    }

    private function powerConsumption(array $matrix): int {
        $mostcommonBits = implode($this->getMostCommonBits($matrix));

        $gammaRate = bindec($mostcommonBits);
        $epsilonRate = bindec($this->invertBits($mostcommonBits));

        return $gammaRate * $epsilonRate;
    }

    private function filterMatrix(array $matrix, int $position, string $filter): array {
        $result = [];

        foreach($matrix as $line) {
            if ($line[$position] === $filter) {
                $result[] = $line;
            }
        }

        return $result;
    }

    private function getRating(array $matrix, $invertFunction): int {
        for ($i = 0; $i < sizeof($matrix[0]); ++$i) {
            $findBit = $invertFunction($this->getMostCommonBitAt($i, $matrix));

            $matrix = $this->filterMatrix($matrix, $i, $findBit);

            if (sizeof($matrix) === 1) {
                return bindec(implode($matrix[0]));
            }
        }

        throw new \ErrorException('there are no lines left');
    }

    private function lifeSupportRating(array $matrix) {
        $oxygenGeneratorRating = $this->getRating($matrix, fn($str) => $str);
        $co2ScrubberRating = $this->getRating($matrix, [$this, 'invertBits']);

        return $oxygenGeneratorRating * $co2ScrubberRating;
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
            '00100',
            '11110',
            '10110',
            '10111',
            '10101',
            '01111',
            '00111',
            '11100',
            '10000',
            '11001',
            '00010',
            '01010'
        ]);
        $this->addTest($this->powerConsumption($testInput), 198);
        $this->addTest($this->lifeSupportRating($testInput), 230);
        
        
        
        $input = $this->readInput(explode(PHP_EOL, $puzzle->input));
        $this->addResult($this->powerConsumption($input), (int)$puzzle->part1);// 3549854
        $this->addResult($this->lifeSupportRating($input), (int)$puzzle->part2);// 3765399
    }
}