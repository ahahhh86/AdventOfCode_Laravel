<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Puzzle>
 */
class PuzzleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year' => '2021',
            'day' => fake()->numberBetween(1, 25),
            'input' => null,
            'part1' => null,
            'part2' => null,
        ];
    }
}
