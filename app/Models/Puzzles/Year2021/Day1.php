<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Depths {
    private $depths;
    private const AVERAGE_COUNT = 3;



    public function __construct(array $stringList) {
        $this->depths = array_map(
            fn($str): int => (int) $str,
            $stringList
        );
    }

    public function countDeeper(): int {
        return $this::countIfNextBigger($this->depths);
    }

    public function countDeeperAverage(): int {
        $sumArray = []; // if sum or average does not matter for comparison, but sum needs less operations
        $loopEnd = sizeof($this->depths) - $this::AVERAGE_COUNT;

        for($i = 0; $i <= $loopEnd; ++$i) {
            $sumArray[] = array_sum(
                array_slice( $this->depths, $i, $this::AVERAGE_COUNT)
            );
        }

        return $this::countIfNextBigger($sumArray);
    }



    private static function countIfNextBigger(array $array): int {
        $result = 0;

        for($i = 1; $i < sizeof($array); ++$i) {
            if ($array[$i] > $array[$i - 1]) {
                ++$result;
            }
        }

        return $result;
    }
}



class Day1 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testDepths = new Depths([
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
        $this->addTest($testDepths->countDeeper(), 7);
        $this->addTest($testDepths->countDeeperAverage(), 5);



        $depths = new Depths(explode(PHP_EOL, $puzzle->input));
        $this->addResult($depths->countDeeper(), (int)$puzzle->part1); // 1759
        $this->addResult($depths->countDeeperAverage(), (int)$puzzle->part2); // 1805
    }
}