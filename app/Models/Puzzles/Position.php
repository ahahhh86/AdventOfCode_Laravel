<?php

namespace App\Models\Puzzles;

class Position {
    public $x = 0;
    public $y = 0;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    public function __toString(): string
    {
        return "$this->x : $this->y";
    }
}