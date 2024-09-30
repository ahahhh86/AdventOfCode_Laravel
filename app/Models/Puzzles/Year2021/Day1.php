<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class OceanFloor {
    private $floor;
    private const AVERAGE_COUNT = 3;

    public function __construct(array $stringList) {
        $this->floor = array_map(
            fn($str): int => (int) $str,
             $stringList
        );
    }

    public function countDeeper(): int {
        return $this::countIfNextBigger($this->floor);
    }
    
    public function countDeeperAverage(): int {
        $sumArray = []; // if sum or average does not matter for comparison, but sum needs less operations

        for($i = 0; $i <= (sizeof($this->floor) - $this::AVERAGE_COUNT); ++$i) {
            $sumArray[] = array_sum(array_slice( $this->floor, $i, $this::AVERAGE_COUNT));
        }
    
        return $this::countIfNextBigger($sumArray);
    }



    private static function countIfNextBigger(array $arr): int {
        $result = 0;
        
        for($i = 1; $i < sizeof($arr); ++$i) {
            if ($arr[$i] > $arr[$i - 1]) {
                ++$result;
            }
        }
    
        return $result;
    }
}

class Day1 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testFloor = new OceanFloor([
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
        $this->addTest($testFloor->countDeeper(), 7);
        $this->addTest($testFloor->countDeeperAverage(), 5);



        $floor = new OceanFloor(explode(PHP_EOL, $puzzle->input));
        $this->addResult($floor->countDeeper(), (int)$puzzle->part1);// 1759
        $this->addResult($floor->countDeeperAverage(), (int)$puzzle->part2);// 1805
    }
}