<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;
use App\Models\Puzzles\Helper;

class Display {
    private const COUNT_1478 = 4;
    private const COUNT_0_TO_9 = 10;
    private const SEGMENT_COUNTS = [
        '1' => 2,
        '4' => 4,
        '7' => 3,
        '8' => 7,
        '069' => 6,
        '235' => 5
    ];
    private const OUTPUT_DIGITS_COUNT = 4;

    private $patterns;
    private $outputs;
    private $identified = [];



    public function __construct(string $str) {
        [$patterns, $outputs] = explode(" | ", $str);
        $this->patterns = self::createPatterns($patterns);
        $this->outputs = self::createPatterns($outputs);
    }

    public function count1478(): int {
        $this->identified = [];
        $this->identify1478();

        return count(array_filter(
            $this->outputs,
            fn($output): bool => in_array($output, $this->identified)
        ));
    }

    public function calculateOutputNumber(): int {
        $this->identifyNumbers();
        $identifyValue = fn($value): string => array_keys($this->identified, $value)[0];

        $result = array_reduce(
            $this->outputs,
            fn($carry, $output): string => $carry . $identifyValue($output),
            ''
        );

        if (strlen($result) !== self::OUTPUT_DIGITS_COUNT) {
            throw new \ErrorException('could not find correct output');
        }

        return (int) $result;
    }



    private static function createPatterns(string $str): array {
        // sorting makes it easier to find matching digits, otherwise the order of segments is random
        return array_map(
            fn($digit): string => Helper::sortString($digit),
            explode(' ',$str)
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
        $filteredSegments = array_filter(
            str_split($digit, 1),
            fn($chr): bool => !str_contains($subtract, $chr)
        );

        return array_reduce(
            $filteredSegments,
            fn($carry, $segment): string => $carry . $segment,
            ''
        );
    }



    private function identify1478(): void {
        if (count($this->identified) === self::COUNT_1478) {return ;}
        $this->identified = [];

        array_walk(
            $this->patterns,
            function($pattern): void {
                switch(strlen($pattern)) {
                    case self::SEGMENT_COUNTS['1']:
                        $this->identified[1] = $pattern;
                        break;
                    case self::SEGMENT_COUNTS['4']:
                        $this->identified[4] = $pattern;
                        break;
                    case self::SEGMENT_COUNTS['7']:
                        $this->identified[7] = $pattern;
                        break;
                    case self::SEGMENT_COUNTS['8']:
                        $this->identified[8] = $pattern;
                        break;
                }
            }
        );

        if (count($this->identified) !== self::COUNT_1478) {
            throw new \ErrorException('could not find all numbers');
        }
    }

    private function identify069($digit): void {
        if (self::isInDigit($this->identified[1], $digit)) {
            if (self::isInDigit($this->identified[4], $digit)) {
                $this->identified[9] = $digit;
            } else {
                $this->identified[0] = $digit;
            }
        } else {
            $this->identified[6] = $digit;
        }
    }

    private function identify235($digit, $reduced4By1): void {
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

    private function identifyNumbers(): void {
        if (sizeof($this->identified) === self::COUNT_0_TO_9) {return ;}

        $this->identify1478();
        $reduced4By1 = self::reduceDigit($this->identified[4], $this->identified[1]);

        array_walk(
            $this->patterns,
            function($digit) use($reduced4By1): void {
                switch (strlen($digit)) {
                    case self::SEGMENT_COUNTS['235']:
                        $this->identify235($digit, $reduced4By1);
                        break;

                    case self::SEGMENT_COUNTS['069']:
                        $this->identify069($digit);
                        break;
                }
            }
        );

        if (sizeof($this->identified) !== self::COUNT_0_TO_9) {
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
            fn($carry, $display): int => $carry + $display->count1478()
        );
    }

    public function accumulateDigits(): int {
        return array_reduce(
            $this->displays,
            fn($carry, $display): int => $carry + $display->calculateOutputNumber()
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