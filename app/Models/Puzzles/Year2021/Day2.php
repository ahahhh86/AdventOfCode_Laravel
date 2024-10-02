<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



class Command {
    private $direction;
    private $value;



    public function __construct(string $str) {
        [$this->direction, $value] = explode(" ", $str);
        $this->value = (int) $value;
    }

    public function getDirection(): string {
        return $this->direction;
    }

    public function getValue(): int {
        return $this->value;
    }
}



class SubPosition {
    protected $horizontalPos = 0;
    protected $depth = 0;



    public function go(array $commands): int {
        array_walk(
            $commands,
            function($command): void {$this->goOnce($command);}
        );

        return $this->horizontalPos * $this->depth;
    }



    private function goOnce(Command $command): void {
        switch ($command->getDirection()) {
            case 'forward':
                $this->goForward($command->getValue());
                break;

            case 'down':
                $this->goDown($command->getValue());
                break;

            case 'up':
                $this->goUp($command->getValue());
                break;

            default:
                throw new \ErrorException("unexpected input: {$command->getDirection()}");
        }
    }

    protected function goForward(int $value): void {
        $this->horizontalPos += $value;
    }

    protected function goDown(int $value): void {
        $this->depth += $value;
    }

    protected function goUp(int $value): void {
        $this->depth -= $value;
    }
}



class SubPosition2 extends SubPosition {
    private $aim = 0;



    protected function goForward(int $value): void {
        $this->horizontalPos += $value * $this->aim;
        $this->depth += $value;
    }

    protected function goDown(int $value): void {
        $this->aim += $value;
    }

    protected function goUp(int $value): void {
        $this->aim -= $value;
    }
}



class Submarine {
    private $commands;

    public function __construct(array $stringList) {
        $this->commands = array_map(
            fn($str): Command => new Command($str),
            $stringList
        );
    }

    public function navigate(SubPosition $subPos): int {
        return $subPos->go($this->commands);
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
        $this->addTest($testSub->navigate(new SubPosition()), 150);
        $this->addTest($testSub->navigate(new SubPosition2()), 900);



        $sub = new Submarine(explode(PHP_EOL, $puzzle->input));
        $this->addResult($sub->navigate(new SubPosition()), (int)$puzzle->part1);// 1882980
        $this->addResult($sub->navigate(new SubPosition2()), (int)$puzzle->part2);// 1971232560
    }
}