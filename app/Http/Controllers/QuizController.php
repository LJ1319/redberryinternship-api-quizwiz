<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
	public function index(Request $request): JsonResponse|Paginator
	{
		$perPage = 9;
		$currentPage = 1;
		$totalPages = ceil(Quiz::all()->count() / $perPage);

		if ($request->query->has('quantity')) {
			$perPage = $request->query('quantity');
		}

		if ($request->query->has('current_page')) {
			$currentPage = $request->query('current_page');

			if ($currentPage > $totalPages) {
				return response()->json(['message' => 'No more quizzes available.'], 422);
			}
		}

		return Quiz::with(['users', 'level', 'categories', 'questions', 'questions.answers'])->simplePaginate(perPage: $perPage, page: $currentPage);
	}
}
