<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Display {
    public $patterns;
    public $outputs;
    private $identified = [];

    public function __construct(string $str) {
        [$patterns, $outputs] = explode(" | ", $str);
        $this->patterns = self::createPatterns($patterns);
        $this->outputs = self::createPatterns($outputs);
    }

    public function count1478(): int {
        $this->identified = [];
        $this->identify1478();

        $result = 0;

        array_walk(
            $this->outputs,
            function($output) use (&$result): void {
                if (in_array($output, $this->identified)) {
                    ++$result;
                }
            }
        );

        return $result;
    }

    public function calculateOutputNumber(): int {
        $this->identifyNumbers();

        $result = array_reduce(
            $this->outputs,
            function($acc, $output): string {
                return $acc . array_keys($this->identified, $output)[0];
            },
            ''
        );

        if (strlen($result) !== 4) {
            throw new \ErrorException('could not find correct output');
        }

        return (int) $result;
    }



    private static function createPatterns(string $str): array {
        $digits = explode(' ',$str);

        return array_map(
            function($digit): string {
                    $segments = str_split($digit, 1);
                    sort($segments); // makes it easier to find matching digits, otherwise the order of segments is random
                    return implode('', $segments);},
            $digits
        );
    }

    private static function isInDigit(string $pattern, string $digit): bool {
        foreach(str_split($pattern, 1) as $segment) {
            if (!str_contains($digit, $segment)) {
                return false;
            }
        }

        return true;
    }

    private static function reduceDigit(string $digit, string $subtract): string {
        $segments = str_split($digit, 1);
        return array_reduce(
            $segments,
            function($acc, $segment) use ($subtract): string {
                    return str_contains($subtract, $segment) ? $acc : $acc . $segment;
            },
            ''
        );
    }

    private function identify1478(): void {
        if (sizeof($this->identified) === 4) {return ;}
        $this->identified = [];
    
        array_walk(
            $this->patterns,
            function($pattern): void {
                switch(strlen($pattern)) {
                    case 2:
                        $this->identified[1] = $pattern;
                        break;
                    case 4:
                        $this->identified[4] = $pattern;
                        break;
                    case 3:
                        $this->identified[7] = $pattern;
                        break;
                    case 7:
                        $this->identified[8] = $pattern;
                        break;
                }
            }
        );

        if (sizeof($this->identified) !== 4) {
            throw new \ErrorException('could not find all numbers');
        }
    }

    private function identifyNumbers(): void {
        if (sizeof($this->identified) === 10) {return ;}

        $this->identify1478();

        $patternLength069 = 6;
        $patternLength235 = 5;
        $reduced4By1 = self::reduceDigit($this->identified[4], $this->identified[1]);

        foreach($this->patterns as $digit) {//TODO: refactor split into smaller methods
            // identify 0, 6 or 9?
            if (strlen($digit) === $patternLength069) {
                if (self::isInDigit($this->identified[1], $digit)) {
                    if (self::isInDigit($this->identified[4], $digit)) {
                        $this->identified[9] = $digit;
                    } else {
                        $this->identified[0] = $digit;
                    }
                } else {
                    $this->identified[6] = $digit;
                }

            // identify 2, 3 or 5?
            } else if (strlen($digit) === $patternLength235) {
                if (self::isInDigit($this->identified[1], $digit)) {
                    $this->identified[3] = $digit;
                } else {
                    if (self::isInDigit($reduced4By1, $digit)) {
                        $this->identified[5] = $digit;
                    } else {
                        $this->identified[2] = $digit;
                    }
                }
            }
        }

        if (sizeof($this->identified) !== 10) {
            throw new \ErrorException('could not find all numbers');
        }
    }
}

class Displays {
    private $displays = [];

    public function __construct(array $stringList) {
        $this->displays = array_map(
            fn($line): Display => new Display($line),
            $stringList
        );
    }

    public function countDigits1478(): int {
        return array_reduce(
            $this->displays,
            fn($acc, $display) => $acc + $display->count1478()
        );
    }

    public function accumulateDigits(): int {
        return array_reduce(
            $this->displays,
            fn($acc, $display): int => $acc + $display->calculateOutputNumber()
        );
    }
}

class Day8 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testDisplays = new Displays([
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
        $testDisplays2 = new Displays(['acedgfb cdfbe gcdfa fbcad dab cefabd cdfgeb eafb cagedb ab | cdfeb fcadb cdfeb cdbaf']);
        $this->addTest($testDisplays->countDigits1478(), 26);
        $this->addTest($testDisplays2->accumulateDigits(), 5353);
        $this->addTest($testDisplays->accumulateDigits(), 61229);



        $displays = new Displays(explode(PHP_EOL, $puzzle->input));
        $this->addResult($displays->countDigits1478(), (int)$puzzle->part1); // 261
        $this->addResult($displays->accumulateDigits(), (int)$puzzle->part2); // 987553
    }
}