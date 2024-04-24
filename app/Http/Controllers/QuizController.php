<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizController extends Controller
{
	public function index()
	{
		$orderColumn = request('order_column', 'created_at');
		if (!in_array($orderColumn, ['name', 'created_at', 'users_count'])) {
			$orderColumn = 'created_at';
		}

		$orderDirection = request('order_direction', 'desc');
		if (!in_array($orderDirection, ['asc', 'desc'])) {
			$orderDirection = 'desc';
		}

		return Quiz::filter(request(['search', 'filter', 'levels', 'categories']))
					->with(['users', 'level', 'categories', 'questions', 'questions.answers'])
					->withCount('users')
					->orderBy($orderColumn, $orderDirection)
					->paginate(9);
	}

	public function get(string $id): Model|Builder
	{
		return Quiz::with(['users', 'level', 'categories', 'questions', 'questions.answers'])
				->withCount(['users', 'categories'])
				->where('id', $id)->firstOrFail();
	}

	public function getSimilar(string $id)
	{
		$quiz = $this->get($id);

		return Quiz::whereHas('categories', function ($query) use ($quiz) {
			$query->whereIn('categories.id', $quiz->categories()->pluck('category_id'))->whereNot('quiz_id', $quiz->id);
		})->whereDoesntHave('users', function ($query) {
			$query->where('users.id', auth()->id());
		})->with(['users', 'level', 'categories', 'questions', 'questions.answers'])->take(3)->get();
	}
}
