<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class AnswerQuestionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		Quiz::all()->each(function () {
			Question::factory(rand(10, 20))->create()->each(function ($question) {
				Answer::factory(rand(1, 3))->create([
					'question_id' => $question->id,
				]);

				Answer::factory(1)->create([
					'question_id' => $question->id,
					'value'       => 1,
				]);
			});
		});
	}
}
