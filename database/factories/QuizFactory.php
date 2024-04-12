<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'name'                   => fake()->word(),
			'description'            => fake()->paragraph(),
			'image'                  => fake()->image(dir: 'public/storage', fullPath: false),
			'points'                 => rand(10, 100),
			'duration'               => fake()->time(),
			'instructions'           => fake()->paragraph(),
			'created_at'             => fake()->dateTime(),
			'updated_at'             => fake()->dateTime(),
		];
	}
}
