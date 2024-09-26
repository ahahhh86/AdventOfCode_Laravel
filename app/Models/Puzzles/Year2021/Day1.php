<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day1 extends Day0 {
    private const COUNT_AVERAGE_OF = 3;



    private function readInput(array $strList): array {
        $result = [];
    
        foreach($strList as $line) {
            $result[] = (int) $line;
        }
    
        return $result;
    }
    
    private function countIfNextBigger(array $arr): int {
        $result = 0;
        
        for($i = 1; $i < sizeof($arr); ++$i) {
            if ($arr[$i] > $arr[$i - 1]) {
                ++$result;
            }
        }
    
        return $result;
    }
    
    private function countIfBiggerAverage(array $arr): int {
        $sumArray = []; // if sum or average does not matter for comparison, but sum needs less operations
    
        for($i = 0; $i <= (sizeof($arr) - $this::COUNT_AVERAGE_OF); ++$i) {
            $sumArray[] = array_sum(array_slice($arr, $i, $this::COUNT_AVERAGE_OF));
        }
    
        return $this->countIfNextBigger($sumArray);
    }

    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
            '199',
            '200',
            '208',
            '210',
            '200',
            '207',
            '240',
            '269',
            '260',
            '263'
        ]);
        $this->addTest($this->countIfNextBigger($testInput), 7);
        $this->addTest($this->countIfBiggerAverage($testInput), 5);



        $input = $this->readInput(explode(PHP_EOL, $puzzle->input));
        $this->addResult($this->countIfNextBigger($input), (int)$puzzle->part1);// 1759
        $this->addResult($this->countIfBiggerAverage($input), (int)$puzzle->part2);// 1805
    }
}