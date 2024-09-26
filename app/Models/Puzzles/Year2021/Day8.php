<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day8 extends Day0 {
    private function createPattern(string $str): array {
        $result = [];
        $digits = explode(" ",$str);

        foreach($digits as $digit) {
            $segments = str_split($digit, 1);
            sort($segments); // makes it easier to find matching digits, otherwise the order of segments is random
            $result[] = implode('', $segments);
        }

        return $result;
    }

    private function readInput(array $arr): array {
        $result = [];

        foreach($arr as $line) {
            [$patterns, $output] = explode(" | ", $line);
            $result[] = ['patterns' => $this->createPattern($patterns), 'output' => $this->createPattern($output)];
        }

        return $result;
    }



    private function countDigits1478(array $notes): int {// TODO: make nicer or use function form part 2
        $SEGMENTS_1 = 2;
        $SEGMENTS_4 = 4;
        $SEGMENTS_7 = 3;
        $SEGMENTS_8 = 7;

        $result = 0;

        foreach($notes as $line) {
            foreach($line['output'] as $digit) {
                switch (strlen($digit)) {
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



    private function isInDigit(string $pattern, string $digit): bool {
        foreach(str_split($pattern, 1) as $letter) {
            if (!str_contains($digit, $letter)) {
                return false;
            }
        }

        return true;
    }

    private function reduceDigit(string $digit, string $subtract): string {
        $result = '';

        foreach(str_split($digit, 1) as $letter) {
            if (!str_contains($subtract, $letter)) {
                $result .= $letter;
            }
        }

        return $result;
    }

    private function identify1478(array $patterns): array {
        $result = [];

        foreach($patterns as $pattern) {
            switch(strlen($pattern)) {
                case 2:
                    $result[1] = $pattern;
                    break;
                case 4:
                    $result[4] = $pattern;
                    break;
                case 3:
                    $result[7] = $pattern;
                    break;
                case 7:
                    $result[8] = $pattern;
                    break;
            }
        }

        if (sizeof($result) !== 4) {
            throw new \ErrorException('could not find all numbers');
        }

        return $result;
    }

    private function identifyNumbers(array $digits): array {
        $patternLength069 = 6;
        $patternLength235 = 5;
        $result = $this->identify1478($digits);
        $reduced4By1 = $this->reduceDigit($result[4], $result[1]);

        foreach($digits as $digit) {
            // identify 0, 6 or 9?
            if (strlen($digit) === $patternLength069) {
                if ($this->isInDigit($result[1], $digit)) {
                    if ($this->isInDigit($result[4], $digit)) {
                        $result[9] = $digit;
                    } else {
                        $result[0] = $digit;
                    }
                } else {
                    $result[6] = $digit;
                }

            // identify 2, 3 or 5?
            } else if (strlen($digit) === $patternLength235) {
                if ($this->isInDigit($result[1], $digit)) {
                    $result[3] = $digit;
                } else {
                    if ($this->isInDigit($reduced4By1, $digit)) {
                        $result[5] = $digit;
                    } else {
                        $result[2] = $digit;
                    }
                }
            }
        }

        if (sizeof($result) !== 10) {
            throw new \ErrorException('could not find all numbers');
        }

        return $result;
    }

    private function calculateOutputNumber(array $note): int {
        $identified = $this->identifyNumbers($note['patterns']);

        $result = "";

        foreach($note['output'] as $output) {
            foreach($identified as $key => $value) {
                if ($output === $value) {
                    $result .= $key;
                    break;
                }
            }
        }

        if (strlen($result) !== 4) {
            throw new \ErrorException('could not find correct output');
        }

        return (int)$result;
    }

    private function accumulateDigits(array $notes): int {
        $result = 0;

        foreach($notes as $note) {
            $result += $this->calculateOutputNumber($note);
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
        $this->addTest($this->countDigits1478($testInput), 26);
        $this->addTest($this->accumulateDigits($testInput2), 5353);
        $this->addTest($this->accumulateDigits($testInput), 61229);



        $input = $this->readInput(explode(PHP_EOL, $puzzle->input));
        $this->addResult($this->countDigits1478($input), (int)$puzzle->part1); // 261
        $this->addResult($this->accumulateDigits($input), (int)$puzzle->part2); // x
    }
}