<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;



enum CaveStatus {
    case Start;
    case End;
    case Small;
    case Big;
}

class Cave {
    private $name;
    private $connections;
    private $Status;

    public function __construct(string $name, string $connection) {
        $this->name = $name;
        $this->connections = [$connection];

        $this->Status = match ($this->name) {
            'start' => CaveStatus::Start,
            'end' => CaveStatus::End,
            default => (strtolower($this->name) === $this->name) ? CaveStatus::Small : CaveStatus::Big
        };
    }

    public function __tostring(): string {
        return $this->name;
    }

    public function isStart(): bool {
        return $this->Status === CaveStatus::Start;
    }

    public function isEnd(): bool {
        return $this->Status === CaveStatus::End;
    }

    public function isSmall(): bool {
        return $this->Status === CaveStatus::Small;
    }

    public function addConnection(string $name): void {
        $this->connections[] = $name;
    }
}



class Caves {
    private $caves = [];



    public function __construct(array $stringList) {
        array_walk(
            $stringList,
            function($str): void {
                [$cave1, $cave2] = explode('-', $str);
                $this->add($cave1, $cave2);
                $this->add($cave2, $cave1);
            }
        );
    }

    public function countPaths(): int {
        $routes = [$this->findStart()];
        $this->countPathsR($routes);
        return sizeof($routes);
    }

    private function add(string $name, string $connection): void {
        if (isset($this->caves[$name])) {
            $this->caves[$name]->addConnection($connection);
        } else {
            $this->caves[$name] = new Cave($name, $connection);
        }
    }

    private function findStart(): string {
        foreach($this->caves as $cave) {
            if ($cave->isStart()) {
                return $cave;
            }
        }

        throw new \ErrorException("can not find the start");
    }

    private function countPathsR(array &$paths): void {
        // TODO:
    }
}



class Day12 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $test1 = new Caves([
            'start-A',
            'start-b',
            'A-c',
            'A-b',
            'b-d',
            'A-end',
            'b-end',
        ]);
        // $test2 = new Connections([
        //     'dc-end',
        //     'HN-start',
        //     'start-kj',
        //     'dc-start',
        //     'dc-HN',
        //     'LN-dc',
        //     'HN-end',
        //     'kj-sa',
        //     'kj-HN',
        //     'kj-dc',
        // ]);
        // $test3 = new Connections([
        //     'fs-end',
        //     'he-DX',
        //     'fs-he',
        //     'start-DX',
        //     'pj-DX',
        //     'end-zg',
        //     'zg-sl',
        //     'zg-pj',
        //     'pj-he',
        //     'RW-he',
        //     'fs-DX',
        //     'pj-RW',
        //     'zg-RW',
        //     'start-pj',
        //     'he-WI',
        //     'zg-he',
        //     'pj-fs',
        //     'start-RW',
        // ]);
        $this->addTest($test1->countPaths(), 10);



        // $caves = new Caves(explode(PHP_EOL, $puzzle->input));
        // $this->addResult($caves->countPaths(), (int)$puzzle->part1); // x
        // $this->addResult($caves->countPaths(), (int)$puzzle->part2); // x
    }
}