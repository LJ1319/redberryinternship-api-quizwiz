<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
	public function index(Request $request): JsonResponse|LengthAwarePaginator
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
				return response()->json(['message' => 'No more quizzes available!'], 422);
			}
		}

		return DB::table('quizzes')->paginate(perPage: $perPage, page: $currentPage);
	}
}
