<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		DB::table('levels')->insert(
			[
				[
					'name'          => 'Starter',
					'bg_color'      => '#F0F9FF',
					'color'    => '#026AA2',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],
				[
					'name'          => 'Beginner',
					'bg_color'      => '#EFF8FF',
					'color'    => '#175CD3',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],
				[
					'name'          => 'Middle',
					'bg_color'      => '#F9F5FF',
					'color'    => '#6941C6',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],	[
					'name'          => 'High',
					'bg_color'      => '#FFFAEB',
					'color'    => '#B54708',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],
				[
					'name'          => 'Very high',
					'bg_color'      => '#FDF2FA',
					'color'    => '#C11574',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],
				[
					'name'          => 'Dangerously high',
					'bg_color'      => '#FFF1F3',
					'color'    => '#C01048',
					'created_at'    => fake()->dateTime(),
					'updated_at'    => fake()->dateTime(),
				],
			]
		);
	}
}
