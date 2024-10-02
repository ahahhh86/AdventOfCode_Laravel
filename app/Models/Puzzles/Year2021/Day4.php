<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Number {
    private $number;
    private $checked = false;



    public function __construct(int $number) {
        $this->number = $number;
    }

    public function getNumber(): int {
        return $this->number;
    }

    public function getScore(): int {
        return $this->checked ? 0 : $this->number;
    }

    public function check(int $number): bool {
        if ($number === $this->number) {
            $this->checked = true;
        }
        return $this->checked;
    }

    public function isChecked(): bool {
        return $this->checked;
    }
}



class Board {
    private $board;
    private $isWon = false;



    public function __construct(array $board) {
        $this->board = $board;
        array_walk_recursive(
            $this->board,
            fn(&$x): Number => $x = new Number($x)
        );
    }

    public function isWon(): bool {
        return $this->isWon;
    }

    public function setMarker(int $number): void {
        $transpose = fn(array $board): array => array_map(null, ...$board);

        array_walk_recursive(
            $this->board,
            fn(&$item): bool => $item->check($number)
        );

        $this->isWon =
            self::isWonHorizontal($this->board) ||
            self::isWonHorizontal($transpose($this->board)); // aka isWonVertical
    }

    public function calculateScore(int $number): int {
        $sum = array_reduce(
            array_merge(...$this->board),
            fn($carry, $item): int => $carry + $item->getScore()
        );

        return $sum * $number;
    }



    private static function isWonHorizontal(array $board): bool {
        $isLineChecked = function(array $line): bool {
            return array_reduce(
                $line,
                fn($carry, $item): bool => $carry && $item->isChecked(),
                true
            );
        };

        return array_reduce(
            $board,
            fn($carry, $line): bool => $carry || $isLineChecked($line),
            false
        );
    }
}

class Bingo {
    private $numbers;
    private $numberIndex = 0;
    private $boards;
    private $boardsWon = 0;

    public function __construct(array $stringList) {
        $this->numbers = explode(',', $stringList[0]);

        array_splice($stringList, 0, 2); // first line are $numbers and second line is empty
        $boards = [];
        $boardNum = 0;

        array_walk(
            $stringList,
            function($str) use (&$boardNum, &$boards): void {
                if ($str === "") {
                    ++$boardNum;
                    $boards[$boardNum] = [];
                    return;
                }

                // small numbers are filled with spaces (' 7'), but every array should have  the same size, so empty arrays nedd to be skipped
                $boards[$boardNum][] = preg_split('/[\s]+/', $str, flags: PREG_SPLIT_NO_EMPTY);
            }
        );

        $this->boards = array_map(fn($board) => new Board($board), $boards);
    }

    public function play(bool $firstWin = false): int {
        // numberIndex used to save the work done in part1 and do not repeat it in part2
        for(; $this->numberIndex < sizeof($this->numbers); ++$this->numberIndex) {
            $number = $this->numbers[$this->numberIndex];

            foreach($this->boards as $board) {
                if ($board->isWon()) {continue;}

                $board->setMarker($number);

                if ($board->isWon()) {
                    ++$this->boardsWon;
                    if ($firstWin || $this->boardsWon === sizeof($this->boards)) {
                        ++$this->numberIndex; // otherwise numberIndex will be called in part1 and part2 with the same value
                        return $board->calculateScore($number);
                    }
                }
            }
        }

        throw new \ErrorException('not each board has won');
    }
}

class Day4 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testBingo = new Bingo([
            '7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1',
            '',
            '22 13 17 11  0',
            ' 8  2 23  4 24',
            '21  9 14 16  7',
            ' 6 10  3 18  5',
            ' 1 12 20 15 19',
            '',
            ' 3 15  0  2 22',
            ' 9 18 13 17  5',
            '19  8  7 25 23',
            '20 11 10 24  4',
            '14 21 16 12  6',
            '',
            '14 21 17 24  4',
            '10 16 15  9 19',
            '18  8 23 26 20',
            '22 11 13  6  5',
            ' 2  0 12  3  7'
        ]);
        $this->addTest($testBingo->play( true), 4512);
        $this->addTest($testBingo->play(), 1924);



        $bingo = new Bingo(explode(PHP_EOL, $puzzle->input));
        $this->addResult($bingo->play( true), (int)$puzzle->part1); // 33348
        $this->addResult($bingo->play(), (int)$puzzle->part2); // 8112
    }
}