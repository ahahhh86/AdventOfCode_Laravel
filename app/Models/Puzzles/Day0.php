<?php

namespace App\Models\Puzzles;



abstract class Day0 {
    protected $tests = [];
    protected $results = [];



    public function getTests(): array {
        return $this->tests;
    }

    public function getResults(): array {
        return $this->results;
    }

    protected function addTest($result, $expected): void {
        $this->tests[] = compact('result', 'expected');
    }

    protected function addResult($result, $expected): void {
        $this->results[] = compact('result', 'expected');
    }
}