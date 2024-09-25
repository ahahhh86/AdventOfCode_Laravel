<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;

class Day4 extends Day0 {
    private function readInput(array $stringList): array {
        $result = [];
        $result['numbers'] = explode(",", $stringList[0]);
        $result['boards'] = [];
        $boardNum=0;
    
        for($i=2; $i < sizeof($stringList); ++$i) {// no foreach, because the first two lines should be skipped
            if ($stringList[$i] === "") {
                ++$boardNum;
                $result['boards'][$boardNum] = [];
                continue;
            }
    
            // small numbers are filled with spaces (" 7"), but every array should have  the same size, so empty arrays nedd to be skipped
            $result['boards'][$boardNum][] = preg_split(pattern: "/[\s]+/", subject: $stringList[$i], flags: PREG_SPLIT_NO_EMPTY);
        }
    
        return $result;
    }
    
    private function areAllElementsTrue(array $array): bool {
        $allElementsSame = count(array_unique($array)) === 1;
        return $allElementsSame && $array[0];
    }
    
    private function isWonHorizontal(array $board): bool {
        foreach($board as $line) {
            if ($this->areAllElementsTrue($line)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function flipBoard(array $board): array {
        $result = [];
    
        for($i=0; $i < sizeof($board); ++$i) {
            for($j=0; $j < sizeof($board[$i]); ++$j) {
                $result[$i][$j] = $board[$j][$i];
            }
        }
    
        return $result;
    }
    
    function isWon(array $board): bool {
        return
            $this->isWonHorizontal($board) ||
            $this->isWonHorizontal($this->flipBoard($board)); // aka isWonVertical
    }
    
    private function createOneCheckBoard(array $board): array {
        $result = [];
    
        foreach($board as $line) {
            $result[] = array_fill(0, sizeof($line), false);
        }
    
        return $result;
    }
    
    private function createCheckBoards(array $board): array {
        $result = [];
    
        foreach($board as $temp) {
            $result[] = $this->createOneCheckBoard($temp);
        }
    
        return $result;
    }
    
    private function check(int $number, array $board, array &$check): void {
        for($i=0; $i < sizeof($board); ++$i) {
            for($j=0; $j < sizeof($board[$i]); ++$j) {
                if ($board[$i][$j] == $number) {
                    $check[$i][$j] = true;
                }
            }
        }
    }
    
    private function calculateScore(int $number, array $board, array $check): int {
        $result = 0;
    
        for($i=0; $i < sizeof($board); ++$i) {
            for($j=0; $j < sizeof($board[$i]); ++$j) {
                if (!$check[$i][$j]) {
                    $result += $board[$i][$j];
                }
            }
        }
    
        $result *= $number;
    
        return $result;
    }

    private function playBingo(array $input, bool $firstWin = false): int {
        $checkBoards = $this->createCheckBoards($input['boards']);
        $boardsWon = array_fill(0, sizeof($checkBoards), false);
    
        foreach($input['numbers'] as $number) {
            for($i=0; $i < sizeof($input['boards']); ++$i) {
                if ($boardsWon[$i]) {continue;}
    
                $this->check($number, $input['boards'][$i], $checkBoards[$i]);
                
                if ($this->isWon($checkBoards[$i])) {
                    $boardsWon[$i] = true;
                    if ($firstWin || $this->areAllElementsTrue($boardsWon)) {
                        return $this->calculateScore($number, $input['boards'][$i], $checkBoards[$i]);
                    }
                }
            }
        }
    
        throw new \ErrorException("not each board has won");
    }



    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
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
        $this->addTest($this->playBingo($testInput, true), 4512);
        $this->addTest($this->playBingo($testInput), 1924);



        $input = $this->readInput(explode("\r\n", $puzzle->input));
        $this->addResult($this->playBingo($input, true), (int)$puzzle->part1); // 33348
        $this->addResult($this->playBingo($input), (int)$puzzle->part2); // 8112
    }
}