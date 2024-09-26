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
        $this->tests[] = ['result' => $result, 'expected' => $expected];
    }

    protected function addResult($result, $expected): void {
        $this->results[] = ['result' => $result, 'expected' => $expected];
    }
}