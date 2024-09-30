<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Board {
    private $board;
    private $isWon = false;

    public function __construct(array $board) {
        $this->board = $board;
        array_walk_recursive($this->board, fn(&$x) => $x = ['num' => (int) $x, 'check' => false]);
    }

    private static function isLineChecked(array $line): bool {
        return array_reduce(
            $line,
            fn($acc, $item): bool => $acc && $item['check'],
            true
        );
    }

    private static function isWonHorizontal(array $board): bool {
        return array_reduce(
            $board,
            fn($acc, $line): bool => $acc || self::isLineChecked(line: $line),
            false
        );
    }

    private static function transpose(array $board): array {
        return array_map(null, ...$board);
    }

    public function isWon(): bool {
        return $this->isWon;
    }

    public function setMarker(int $number): void {
        array_walk($this->board, function(&$line) use ($number): void{
            array_walk($line, function(&$item) use ($number): void {
                if ($item['num'] === $number) {
                    $item['check'] = true;
                }
            });
        });

        $this->isWon =
            self::isWonHorizontal($this->board) ||
            self::isWonHorizontal(self::transpose($this->board)); // aka isWonVertical
    }

    public function calculateScore(int $number): int {
        $result = 0;

        array_walk($this->board, function($line) use (&$result): void{
            array_walk($line, function($item) use (&$result): void {
                        if (!$item['check']) {
                            $result += $item['num'];
                        }
            });
        });

        $result *= $number;

        return $result;
    }
}

class Bingo {
    private $numbers;
    private $numberIndex = 0;
    private $boards;
    private $boardsWon = 0;

    public function __construct(array $stringList) {
        $this->numbers = explode(',', $stringList[0]);

        array_splice($stringList, 0, 2);
        $boards = [];
        $boardNum=0;

        foreach($stringList as $str) {
            if ($str === "") {
                ++$boardNum;
                $boards[$boardNum] = [];
                continue;
            }

            // small numbers are filled with spaces (' 7'), but every array should have  the same size, so empty arrays nedd to be skipped
            $boards[$boardNum][] = preg_split(pattern: '/[\s]+/', subject: $str, flags: PREG_SPLIT_NO_EMPTY);
        }
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