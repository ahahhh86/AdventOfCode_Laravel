<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Stack {
    private $line;
    
    

    public function __construct(string $str) {
        if (!preg_match('/[\(\[{<>}\)\]]+/', $str)) {
            throw new \ErrorException("unexpected input: {$str}");
        }
        $this->line = str_split($str, 1);
    }

    public function walk() {
        $stack = [];

        array_walk(
            $line,
            function($char) use(&$stack) {
                switch ($char) {
                    case '(':
                    case '[':
                    case '{':
                    case '<':
                        $stack[] = self::getClosing($char);
                        break;
                    
                    case ')':
                    case ']':
                    case '}':
                    case '>':
                        # code...
                        break;
                    
                    default:
                        throw new \ErrorException("unexpected input: {$char}");
                }
            }
        );

    }

    private static function getScore(string $chunkCharacter): int {
        return match ($chunkCharacter) {
            ')' => 3,
            ']' => 57,
            '}' => 1197,
            '>' => 25137,
            default => throw new \ErrorException("unexpected input: {$chunkCharacter}")
        };
    }


    private static function getClosing(string $open): string {
        return match ($open) {
            '(' => ')',
            '[' => ']',
            '{' => '}',
            '<' => '>',
            default => throw new \ErrorException("unexpected input: {$open}")
        };
    }
}



class Day1 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testdepths = new Depths([
            '[({(<(())[]>[[{[]{<()<>>',
            '[(()[<>])]({[<{<<[]>>(',
            '{([(<{}[<>[]}>{[]{[(<()>',
            '(((({<>}<{<{<>}{[]{[]{}',
            '[[<[([]))<([[{}[[()]]]',
            '[{[{({}]{}}([{[{{{}}([]',
            '{<[[]]>}<{[{[{[]{()[[[]',
            '[<(<(<(<{}))><([]([]()',
            '<{([([[(<>()){}]>(<<{{',
            '<{([{{}}[<[[[<>{}]]]>[]]',
        ]);
        // $this->addTest($testdepths->countDeeper(), 7);
        // $this->addTest($testdepths->countDeeperAverage(), 5);



        // $depths = new Depths(explode(PHP_EOL, $puzzle->input));
        // $this->addResult($depths->countDeeper(), (int)$puzzle->part1);// 1759
        // $this->addResult($depths->countDeeperAverage(), (int)$puzzle->part2);// 1805
    }
}