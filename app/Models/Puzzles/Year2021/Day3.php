<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class BinaryMatrix {
    private $matrix = [];



    public function __construct(array $stringList) {
        $this->matrix = array_map(
            fn($str): array => str_split($str, 1),
            $stringList
        );
    }

    public function powerConsumption(): int {
        $mostcommonBits = implode('', $this->getMostCommonBits());

        $gammaRate = bindec($mostcommonBits);
        $epsilonRate = bindec($this->invertBits($mostcommonBits));

        return $gammaRate * $epsilonRate;
    }

    public function lifeSupportRating(): int {
        $oxygenGeneratorRating = $this->getRating($this->matrix, fn($str) => $str);
        $co2ScrubberRating = $this->getRating($this->matrix, [$this, 'invertBits']);

        return $oxygenGeneratorRating * $co2ScrubberRating;
    }



    private static function getMostCommonBitAt(array $matrix, int $position): string {
        $count = array_count_values(array_column($matrix, $position));
        $count = [$count[0] ?? 0, $count[1] ?? 0];  // Just in case there are only '0' or only '1'
        return ($count[0] > $count[1]) ? '0' : '1'; // returns 1 also if count1 == count0, but the puzzle (part 2) expects this
    }

    private function getMostCommonBits(): array {
        $result = [];

        for($i = 0; $i < sizeof($this->matrix[0]); ++$i) {
            $result[] = $this->getMostCommonBitAt($this->matrix, $i);
        }

        return $result;
    }

    private static function invertBits(string $str): string {
        $charArray = str_split($str, 1);
        return array_reduce(
            $charArray,
            fn($carry, $chr): string => match ($chr) {
                '0' => "{$carry}1",
                '1' => "{$carry}0",
                default => throw new \ErrorException("unexpected input: {$chr}")
            },
            ''
        );
    }

    private function filterMatrix(array $matrix, int $position, string $filter): array {
        return array_values(
            array_filter(
                $matrix,
                fn($line): bool => $line[$position] === $filter
            )
        );
    }

    private function getRating(array $matrix, $invertFunction): int {
        for ($i = 0; $i < sizeof($matrix[0]); ++$i) {
            $findBit = $invertFunction($this->getMostCommonBitAt($matrix, $i));

            $matrix = $this->filterMatrix($matrix, $i, $findBit);

            if (sizeof($matrix) === 1) {
                return bindec(implode('', $matrix[0]));
            }
        }

        throw new \ErrorException('there are no lines left');
    }
}



class Day3 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testMatrix = new BinaryMatrix([
            '00100',
            '11110',
            '10110',
            '10111',
            '10101',
            '01111',
            '00111',
            '11100',
            '10000',
            '11001',
            '00010',
            '01010'
        ]);
        $this->addTest($testMatrix->powerConsumption(), 198);
        $this->addTest($testMatrix->lifeSupportRating(), 230);



        $matrix = new BinaryMatrix(explode(PHP_EOL, $puzzle->input));
        $this->addResult($matrix->powerConsumption(), (int)$puzzle->part1);// 3549854
        $this->addResult($matrix->lifeSupportRating(), (int)$puzzle->part2);// 3765399
    }
}