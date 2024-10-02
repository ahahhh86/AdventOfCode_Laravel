<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Chunks {
    private $chunks;
    private $isCorrupted = false;
    private $score = 0;
    private $closingStack = [];



    public function __construct(string $str) {
        $this->chunks = str_split($str, 1);
        $this->checkCorrupted();
        $this->calculateIncompleteScore();
    }

    public function getScore(): int {
        return $this->score;
    }

    public function isCorrupted(): bool {
        return $this->isCorrupted;
    }



    private static function getCorruptedScore(string $closing): int {
        return match ($closing) {
            ')' => 3,
            ']' => 57,
            '}' => 1197,
            '>' => 25137,
            default => throw new \ErrorException("unexpected input: {$closing}")
        };
    }

    private static function getIncompleteScore(string $closing): int {
        return match ($closing) {
            ')' => 1,
            ']' => 2,
            '}' => 3,
            '>' => 4,
            default => throw new \ErrorException("unexpected input: {$closing}")
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

    private function checkCorrupted(): void {
        foreach($this->chunks as $char) {
            switch ($char) {
                case '(':
                case '[':
                case '{':
                case '<':
                    $this->closingStack[] = self::getClosing($char);
                    break;

                case ')':
                case ']':
                case '}':
                case '>':
                    $closing = array_pop($this->closingStack);
                    if ($closing !== $char) {
                        $this->isCorrupted = true;
                        $this->score = self::getCorruptedScore($char);
                        return;
                    }
                    break;

                default:
                    throw new \ErrorException("unexpected input: {$char}");
            }
        }
    }

    private function calculateIncompleteScore(): void {
        if ($this->isCorrupted) {return;}

        $completeArray = array_reverse($this->closingStack);
        $this->score = array_reduce(
            $completeArray,
            fn($carry, $item): int => $carry * 5 + self::getIncompleteScore($item)
        );
    }
}



class NavigationSubsystem {
    private $lines;



    public function __construct(array $stringList) {
        $this->lines = array_map(
            fn($item): Chunks => new Chunks($item),
            $stringList
        );
    }

    public function getScorePart1(): int {
        return array_reduce(
            array_filter(
                $this->lines,
                fn($item): bool => $item->isCorrupted()
            ),
            fn($carry, $item): int => $carry + $item->getScore()
        );
    }

    public function getScorePart2(): int {
        $scores = array_map(
            fn($item): int => $item->getScore(),
            array_filter(
                $this->lines,
                fn($item) => !$item->isCorrupted()
            )
        );

        sort($scores);
        $index = (int) (sizeof($scores)/2);
        return $scores[$index];
    }
}



class Day10 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testSystem = new NavigationSubsystem([
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
        $this->addTest($testSystem->getScorePart1(), 26397);
        $this->addTest($testSystem->getScorePart2(), 288957);



        $system = new NavigationSubsystem(explode(PHP_EOL, $puzzle->input));
        $this->addResult($system->getScorePart1(), (int)$puzzle->part1); // 341823
        $this->addResult($system->getScorePart2(), (int)$puzzle->part2); // 2801302861
    }
}