<?php

namespace App\Models\Puzzles;



class MyArray {
    public static function count_if(array $array, callable $callback): int {
        return array_reduce(
            $array,
            fn($carry, $item): int => $carry + ($callback($item) ? 1 : 0),
            0
        );
    }

    // changed to count_if(array_merge(...$array), callable $callback)
    // public static function count_if_recursive(array $array, callable $callback): int {
    //     return array_reduce(
    //         $array,
    //         function($carry, $line) use($callback): int {
    //             if (gettype($line) === 'array') {
    //                 return $carry + self::count_if_recursive(
    //                 $line,
    //                 $callback
    //                 );
    //             } else {
    //                 return $carry + ($callback($line) ? 1 : 0);
    //             }
    //         }
    //     );
    // }

    // changed to array_reduce(array_merge(...$array), callable $callback)
    // public static function reduce_recursive(array $array, callable $callback, mixed $initial = null): int {
    //     return array_reduce(
    //         $array,
    //         function($carry, $line) use($callback, $initial): int {
    //             if (gettype($line) === 'array') {
    //                 return $carry + self::reduce_recursive(
    //                 $line,
    //                 $callback,
    //                 $initial
    //                 );
    //             } else {
    //                 return $callback($carry, $line);
    //             }
    //         }
    //     );
    // }



    private function __construct() {
        // do not create an instance of MyArray, contains only static methods
    }
}