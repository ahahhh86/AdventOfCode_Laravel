<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Submarine {
    private $commands;

    public function __construct(array $stringList) {
        $this->commands = array_map(
            function($str): array {
                $command = explode(" ", $str);
                return ['direction' => $command[0], 'value' => (int) $command[1]];
            },
             $stringList
        );
    }

    public function navigate(bool $part2 = false): int {
        $horizontalPos = 0;
        $depth = 0;
        $aim = $part2 ? 0 : 1;
        $targetUpDown = &${$part2 ? 'aim' : 'depth'};

        foreach($this->commands as $command) {
            switch ($command['direction']) {
                case 'forward':
                    $horizontalPos += $command['value'] * $aim;
                    if ($part2) {$depth += $command['value'];}
                    break;

                case 'down':
                    $targetUpDown += $command['value'];
                    break;

                case 'up':
                    $targetUpDown -= $command['value'];
                    break;

                default:
                    throw new \ErrorException("unexpected input: {$command['direction']}");
            }
        }

        return $horizontalPos * $depth;
    }
}

class Day2 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $testSub = new Submarine([
            'forward 5',
            'down 5',
            'forward 8',
            'up 3',
            'down 8',
            'forward 2'
        ]);
        $this->addTest($testSub->navigate(), 150);
        $this->addTest($testSub->navigate(true), 900);



        $sub = new Submarine(explode(PHP_EOL, $puzzle->input));
        $this->addResult($sub->navigate(), (int)$puzzle->part1);// 1882980
        $this->addResult($sub->navigate(true), (int)$puzzle->part2);// 1971232560
    }
}