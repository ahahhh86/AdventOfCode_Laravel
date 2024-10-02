<?php

namespace App\Models\Puzzles;

class Helper {
    public static function sortString(string $str): string {
        $array = str_split($str, 1);
        sort($array);
        return implode('', $array);
    }



    private function __construct() {
        // only has static methods, so do not create an instance
    }
}