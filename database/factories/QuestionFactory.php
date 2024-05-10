<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$quiz_id = Quiz::inRandomOrder()->first();

		return [
			'quiz_id' => $quiz_id,
			'body'    => fake()->unique()->sentence(),
			'points'  => fake()->numberBetween(1, 4),
		];
	}
}
