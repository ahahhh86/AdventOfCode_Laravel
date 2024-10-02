<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;


class Octopus {
    private const MAX_ENERGY = 9;

    private $energy;
    private $hasFlashed = false;



    public function __construct(int $energy) {
        $this->energy = $energy;
    }

    public function tryFlash(): bool {
        ++$this->energy;

        if ($this->hasFlashed || !($this->energy > self::MAX_ENERGY)) {
            return false;
        }

        $this->hasFlashed = true;
        return true;
    }

    public function hasFlashed(): bool {
        if (!$this->hasFlashed) {return false;}

        $this->hasFlashed = false;
        $this->energy = 0;
        return true;
    }

    // for debugging
    public function __toString(): string
    {
        return (string) $this->energy;
    }
}



class Octopuses {
    private const STEP_COUNT = 100;
    private const OCTOPUS_COUNT = 100;

    private $octopuses;
    private $allFlashedSteps = 0;



    public function __construct(array $stringList) {
        $this->octopuses = array_map(
            fn($line): array => array_map(
                fn($octopus): Octopus => new Octopus($octopus),
                str_split($line, 1)
            ),
            $stringList
        );
    }

    // for debugging
    public function __toString(): string {
        return array_reduce(
            $this->octopuses,
            fn($carry, $line): string => $carry . implode('', $line) . PHP_EOL,
            ''
        );
    }

    public function takeSteps(): int {
        $flashCount = 0;
        for($i = 0; $i < self::STEP_COUNT; ++$i) {
            $newFlashes = $this->takeOneStep();
            $flashCount += $newFlashes;

            if ($newFlashes === self::OCTOPUS_COUNT && $this->allFlashedSteps === 0) {
                $this->allFlashedSteps = $i + 1;
            }
        }

        return $flashCount;
    }

    public function whenAllFlash(): int {
        if ($this->allFlashedSteps > 0) {
            return $this->allFlashedSteps;
        }

        for($i = self::STEP_COUNT + 1;; ++$i) {
            if (self::OCTOPUS_COUNT === $this->takeOneStep()) {
                return $i;
            }
        }
    }



    private function tryFlashAt(int $x, int $y): void {
        if (!$this->octopuses[$x][$y]->tryFlash()) {return;}

        $postions = [
            [-1, -1], [-1, 0], [-1, 1],
            [0, -1], [0, 1],
            [1, -1], [1, 0], [1, 1],
        ];
        array_walk(
            $postions,
            function ($pos) use ($x, $y): void {
                $newX = $x + $pos[0];
                $newY = $y + $pos[1];
                if (isset($this->octopuses[$newX][$newY])) {
                    $this->tryFlashAt($newX, $newY);
                }
            }
        );
    }

    private function tryFlash(): void {
        $endX = sizeof($this->octopuses);
        $endY = sizeof($this->octopuses[0]);

        for($x = 0; $x < $endX; ++$x) {
            for($y = 0; $y < $endY; ++$y) {
                $this->tryFlashAt($x, $y);
            }
        }
    }

    private function hadFlashed(): int {
        $flashCount = 0;
        array_walk_recursive(
            $this->octopuses,
            function($octopus) use(&$flashCount): void {
                if ($octopus->hasFlashed()) {
                    ++$flashCount;
                }
            }
        );
        return $flashCount;
    }

    private function takeOneStep(): int {
        $this->tryFlash();
        return $this->hadFlashed();
    }
}



class Day11 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testOctopuses = new Octopuses([
            '5483143223',
            '2745854711',
            '5264556173',
            '6141336146',
            '6357385478',
            '4167524645',
            '2176841721',
            '6882881134',
            '4846848554',
            '5283751526'
        ]);
        $this->addTest($testOctopuses->takeSteps(), 1656);
        $this->addTest($testOctopuses->whenAllFlash(), 195);



        $octopuses = new Octopuses(explode(PHP_EOL, $puzzle->input));
        $this->addResult($octopuses->takeSteps(), (int)$puzzle->part1); // 1683
        $this->addResult($octopuses->whenAllFlash(), (int)$puzzle->part2); // 788
    }
}