<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
	public function index(): AnonymousResourceCollection
	{
		$orderColumn = request('order_column', 'created_at');
		if (!in_array($orderColumn, ['name', 'created_at', 'users_count'])) {
			$orderColumn = 'created_at';
		}

		$orderDirection = request('order_direction', 'desc');
		if (!in_array($orderDirection, ['asc', 'desc'])) {
			$orderDirection = 'desc';
		}

		$quizzes = Quiz::filter(request(['search', 'filter', 'levels', 'categories']))
			->with(['users', 'level', 'categories', 'questions', 'questions.answers'])
			->withCount('users')
			->orderBy($orderColumn, $orderDirection)
			->paginate(9);

		return QuizResource::collection($quizzes);
	}

	public function get(string $id): QuizResource
	{
		$quiz = Quiz::with(['users', 'level', 'categories', 'questions', 'questions.answers'])
			->where('id', $id)->firstOrFail();

		return new QuizResource($quiz);
	}

	public function getSimilar(string $id): AnonymousResourceCollection
	{
		$quiz = $this->get($id);

		$similarQuizzes = Quiz::whereHas('categories', function ($query) use ($quiz) {
			$query->whereIn('categories.id', $quiz->categories()->pluck('category_id'))
					->whereNot('quiz_id', $quiz->id);
		})->whereDoesntHave('users', function ($query) {
			$query->where('users.id', auth()->id());
		})->with(['users', 'level', 'categories', 'questions', 'questions.answers'])
			->take(3)
			->get();

		return QuizResource::collection($similarQuizzes);
	}

	public function submit(string $id): JsonResponse
	{
		$checkedAnswers = request('checkedAnswers');

		$quiz = Quiz::findOrFail($id);
		$questions = $quiz->questions()->with('answers')->get();

		$correctAnswersIds = $questions->mapWithKeys(function ($question) {
			return [$question->id => $question->answers
				->filter(fn ($answer) => $answer->value)
				->pluck('id')
				->toArray()];
		});

		$score = 0;
		$mistakes = 0;

		foreach ($checkedAnswers as $questionId => $selectedAnswers) {
			$correctAnswerIdsForQuestion = $correctAnswersIds[$questionId];

			if ($correctAnswerIdsForQuestion === $selectedAnswers) {
				$score += 1;
			} else {
				$mistakes += 1;
			}
		}

		DB::table('quiz_user')->insert([
			'quiz_id'       => $id,
			'user_id'       => auth()->id() ?? null,
			'time'          => request('time'),
			'score'         => $score,
			'created_at'    => Carbon::now(),
			'updated_at'    => Carbon::now(),
		]);

		return response()->json([
			'message'    => 'Your results successfully saved.',
			'results'    => [
				'score'    => $score,
				'mistakes' => $mistakes,
			],
		], 201);
	}
}
