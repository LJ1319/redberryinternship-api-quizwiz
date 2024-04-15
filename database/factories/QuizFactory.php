<?php

namespace Database\Factories;

use App\Models\Level;
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
		$level_id = rand(1, Level::all()->count());

		return [
			'name'         => fake()->words(3, true),
			'level_id'     => $level_id,
			'description'  => fake()->paragraph(),
			'image'        => fake()->image(dir: 'public/storage', fullPath: false),
			'duration'     => fake()->time(max: 20 * 60),
			'instructions' => fake()->paragraph(),
			'created_at'   => fake()->dateTime(),
			'updated_at'   => fake()->dateTime(),
		];
	}
}
