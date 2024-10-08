<?php

namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

use function PHPUnit\Framework\isNull;

class Cave {
    private $name;
    private $connections = [];



    public function __construct(string $name) {
        $this->name = $name;
    }

    public function __tostring(): string {
        return $this->name;
    }

    public function addConnection(Cave $connection): void {
        if ($connection->__tostring() === 'start' || $this->name === 'end') {return;}
        $this->connections[$connection->__tostring()] = $connection;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getConnections(): array {
        return $this->connections;
    }
}

class MapNode {
    private $previous = null;
    // private $next = [];
    private $cave;
    // private $path = null; // TODO: maybe needed to increase speed

    public function __construct(Cave $cave, ?MapNode &$previous) {
        if (isset($previous)) {$this->previous = &$previous;}
        $this->cave = $cave;
    }

    public function getPath(): array {
        if (isNull($this->previous)) {
            return [$this->cave->getName()];
        } else {
            return array_merge($this->previous->getPath(), [$this->cave->getName()]);
        }
    }

    public function createNext(array &$caves): array {
        $buffer = [];

        foreach($this->cave->getConnections() as $nextCave) {
            if (strtoupper($nextCave->getName()) === $nextCave->getName()) {
                $buffer[] = new MapNode($nextCave, $this);
            }
        }// TODO:
        $result = [];
        foreach($buffer as $cave) {
            $result = array_merge($result, $cave->createNext($caves));
        }

        return $result;
    }
}

class Map {
    private $caveNodes = [];

    public function __construct(array &$caves) {
        $nullRef = null;
        $this->caveNodes[] = new MapNode($caves['start'], $nullRef);
        $this->caveNodes = array_merge($this->caveNodes, $this->caveNodes[0]->createNext($caves));
        dd($this->caveNodes);
    }
}

class Connections {
    private $connections;
    private $caves = [];



    public function __construct(array $stringList) {
        $this->connections = array_map(
            fn($str) => explode('-', $str),
            $stringList
        );

        $this->createCaves();
        $this->addConnections();
    }

    private function createCaves(): void {
        $names = array_merge(...$this->connections);
        $names = array_unique($names);

        array_walk(
            $names,
            fn($str): Cave => $this->caves[$str] = new Cave($str),
        );
    }

    private function addConnections(): void {
        foreach($this->connections as $connection) {
            [$name1, $name2] = [$connection[0], $connection[1]];
            $this->caves[$name1]->addConnection($this->caves[$name2]);
            $this->caves[$name2]->addConnection($this->caves[$name1]);
        }
        $x = new Map($this->caves);
    }
}



class Day12 extends Day0 {
    public function __construct(Puzzle $puzzle) {
        $test1 = new Connections([
            'start-A',
            'start-b',
            'A-c',
            'A-b',
            'b-d',
            'A-end',
            'b-end',
        ]);
        dd($test1);
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
        // $this->addTest($test1->countPaths(), 10);



        // $caves = new Caves(explode(PHP_EOL, $puzzle->input));
        // $this->addResult($caves->countPaths(), (int)$puzzle->part1); // x
        // $this->addResult($caves->countPaths(), (int)$puzzle->part2); // x
    }
}