<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\Pagination\Paginator;

class QuizController extends Controller
{
	public function index(): Paginator
	{
		return Quiz::with(['users', 'level', 'categories', 'questions', 'questions.answers'])->paginate(9);
	}
}
