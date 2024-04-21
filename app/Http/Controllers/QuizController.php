<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

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
}
