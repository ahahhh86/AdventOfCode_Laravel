<?php



namespace App\Models\Puzzles\Year2021;

use App\Models\Puzzle;
use App\Models\Puzzles\Day0;

class Day2 extends Day0 {
    private function readInput(array $strList): array {
        $result = [];
    
        foreach($strList as $str) {
            $item = explode(" ", $str);
            $result[] = ['direction' => $item[0], 'value' => (int)$item[1]];
        }
    
        return $result;
    }
    
    private function navigate(array $instructions): int {
        $horizontalPos = 0;
        $depth = 0;
    
        foreach($instructions as $instruction) {
            switch ($instruction['direction']) {
                case 'forward':
                    $horizontalPos += $instruction['value'];
                    break;
    
                case 'down':
                    $depth += $instruction['value'];
                    break;
    
                case 'up':
                    $depth -= $instruction['value'];
                    break;
                
                default:
                    throw new \ErrorException("unexpected input: {$instruction['direction']}");
            }
        }
    
        return $horizontalPos * $depth;
    } 
    
    private function navigate2(array $instructions): int {
        $horizontalPos = 0;
        $depth = 0;
        $aim = 0;
    
        foreach($instructions as $instruction) {
            switch ($instruction['direction']) {
                case 'forward':
                    $horizontalPos += $instruction['value'] * $aim;
                    $depth += $instruction['value'];
                    break;
    
                case 'down':
                    $aim += $instruction['value'];
                    break;
    
                case 'up':
                    $aim -= $instruction['value'];
                    break;
                
                default:
                    throw new \ErrorException("unexpected input: {$instruction['direction']}");
            }
        }
    
        return $horizontalPos * $depth;
    }

    public function __construct(Puzzle $puzzle) {
        $testInput = $this->readInput([
            'forward 5',
            'down 5',
            'forward 8',
            'up 3',
            'down 8',
            'forward 2'
        ]);
        $this->addTest($this->navigate($testInput), 150);
        $this->addTest($this->navigate2($testInput), 900);
        
        
        
        $input = $this->readInput(explode(PHP_EOL, $puzzle->input));
        $this->addResult($this->navigate($input), (int)$puzzle->part1);// 1882980
        $this->addResult($this->navigate2($input), (int)$puzzle->part2);// 1971232560
    }
}