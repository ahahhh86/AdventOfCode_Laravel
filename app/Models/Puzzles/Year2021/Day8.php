<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day8 extends Day0 {
    private function createInput(string $str) {
        $result = [];
        $digits = explode(" ",$str);

        foreach($digits as $digit) {
            $buffer = str_split($digit, 1);
            sort($buffer);
            $result[] = $buffer;
        }

        return $result;
    }

    private function readInput(array $arr): array {
        $result = [];

        foreach($arr as $line) {
            $tmp = explode(" | ", $line);
            $result[] = ['patterns' => $this->createInput($tmp[0]), 'output' => $this->createInput($tmp[1])];
        }

        return $result;
    }

    private function countDigits1478(array $arr): int {
        $SEGMENTS_1 = 2;
        $SEGMENTS_4 = 4;
        $SEGMENTS_7 = 3;
        $SEGMENTS_8 = 7;

        $result = 0;

        foreach($arr as $line) {
            foreach($line['output'] as $digit) {
                switch (sizeof($digit)) {
                    case $SEGMENTS_1:
                    case $SEGMENTS_4:
                    case $SEGMENTS_7:
                    case $SEGMENTS_8:
                        ++$result;
                }
            }
        }

        return $result;
    }

    private function identify1478(array $patterns, int $digit): array {
        $patternLength = match ($digit) {
            1 => 2,
            4 => 4,
            7 => 3,
            8 => 7,
            default => throw new \ErrorException("unexpected input: {$digit}")
        };

        foreach($patterns as $pattern) {
            if (sizeof($pattern) === $patternLength) {
                return $pattern;
            }
        }

        return [];
    }

    private function isInArray(array $pattern, array $source): bool {
        $str = implode("", $source);

        foreach($pattern as $letter) {
            if (!str_contains($str, $letter)) {
                return false;
            }
        }

        return true;
    }

    private function getDifference(array $a, array $b): array {
        $result = [];
        $str = implode("", $b);

        foreach($a as $letter) {
            if (!str_contains($str, $letter)) {
                $result[] = $letter;
            }
        }

        return $result;
    }

    private function identify069(array $patterns, int $digit, array $identified): array {
        $patternLength = 6;

        foreach($patterns as $pattern) {
            if (sizeof($pattern) === $patternLength) {
                switch($digit) {
                    case 0:
                        if ($this->isInArray($identified[1], $pattern) && !$this->isInArray($identified[4], $pattern)) {
                            return $pattern;
                        }
                        break;
                    case 6:
                        $difference80 = $this->getDifference($identified[8], $identified[0]);
                        if (!$this->isInArray($identified[1], $pattern) && $this->isInArray($difference80, $pattern)) {
                            return $pattern;
                        }
                        break;
                    case 9:
                        if ($this->isInArray($identified[1], $pattern) && $this->isInArray($identified[4], $pattern)) {
                            return $pattern;
                        }
                        break;
                    default: throw new \ErrorException("unexpected input: {$digit}");
                }
            }
        }

        return [];
    }

    private function identify235(array $patterns, int $digit, array $identified): array {
        $patternLength = 5;
        $difference41 = $this->getDifference($identified[4], $identified[1]);

        foreach($patterns as $pattern) {
            if (sizeof($pattern) === $patternLength) {
                switch($digit) {
                    case 2:
                        if (!$this->isInArray($difference41, $pattern) && !$this->isInArray($identified[1], $pattern)) {
                            return $pattern;
                        }
                        break;
                    case 3:
                        if ($this->isInArray($identified[1], $pattern)) {
                            return $pattern;
                        }
                        break;
                    case 5:
                        if ($this->isInArray($difference41, $pattern)) {
                            return $pattern;
                        }
                        break;
                    default: throw new \ErrorException("unexpected input: {$digit}");
                }
            }
        }

        return [];
    }

    private function calculateOutputNumber(array $arr): int {
        $identified = [
            1 => $this->identify1478($arr['patterns'], 1),
            4 => $this->identify1478($arr['patterns'], 4),
            7 => $this->identify1478($arr['patterns'], 7),
            8 => $this->identify1478($arr['patterns'], 8),
        ];
        $identified[0] = $this->identify069($arr['patterns'], 0, $identified);
        $identified[6] = $this->identify069($arr['patterns'], 6, $identified);
        $identified[9] = $this->identify069($arr['patterns'], 9, $identified);
        $identified[2] = $this->identify235($arr['patterns'], 2, $identified);
        $identified[3] = $this->identify235($arr['patterns'], 3, $identified);
        $identified[5] = $this->identify235($arr['patterns'], 5, $identified);

        $result = "";

        foreach($arr['output'] as $output) {
            foreach($identified as $key => $value) {
                if ($output == $value) {
                    $result .= $key;
                    break;
                }
            }
        }

        if (strlen($result) !== 4) {
            throw new \ErrorException("could not find correct output");
        }

        return (int)$result;
    }

    private function accumulateDigits(array $arr): int {
        $result = 0;

        foreach($arr as $line) {
            $result += $this->calculateOutputNumber($line);
        }

        return $result;
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
            'be cfbegad cbdgef fgaecd cgeb fdcge agebfd fecdb fabcd edb | fdgacbe cefdb cefbgd gcbe',
            'edbfga begcd cbg gc gcadebf fbgde acbgfd abcde gfcbed gfec | fcgedb cgb dgebacf gc',
            'fgaebd cg bdaec gdafb agbcfd gdcbef bgcad gfac gcb cdgabef | cg cg fdcagb cbg',
            'fbegcd cbd adcefb dageb afcb bc aefdc ecdab fgdeca fcdbega | efabcd cedba gadfec cb',
            'aecbfdg fbg gf bafeg dbefa fcge gcbea fcaegb dgceab fcbdga | gecf egdcabf bgf bfgea',
            'fgeab ca afcebg bdacfeg cfaedg gcfdb baec bfadeg bafgc acf | gebdcfa ecba ca fadegcb',
            'dbcfg fgd bdegcaf fgec aegbdf ecdfab fbedc dacgb gdcebf gf | cefg dcbef fcge gbcadfe',
            'bdfegc cbegaf gecbf dfcage bdacg ed bedf ced adcbefg gebcd | ed bcgafe cdgba cbgef',
            'egadfb cdbfeg cegd fecab cgb gbdefca cg fgcdab egfdb bfceg | gbdfcae bgc cg cgb',
            'gcafb gcf dcaebfg ecagb gf abcdeg gaef cafbge fdbac fegbdc | fgae cfgab fg bagce'
        ]);
        $testInput2 = $this->readInput(['acedgfb cdfbe gcdfa fbcad dab cefabd cdfgeb eafb cagedb ab | cdfeb fcadb cdfeb cdbaf']);
        // $this->addTest($this->countDigits1478($testInput), 26);
        $this->addTest($this->accumulateDigits($testInput2), 5353);
        $this->addTest($this->accumulateDigits($testInput), 61229);



        $input = $this->readInput(explode("\r\n", $puzzle->input));
        $this->addResult($this->countDigits1478($input), (int)$puzzle->part1); // 261
        $this->addResult($this->accumulateDigits($input), (int)$puzzle->part2); // x
    }
}