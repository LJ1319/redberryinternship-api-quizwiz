<?php

namespace App\Http\Controllers;

use App\Models\Quiz;

class QuizController extends Controller
{
	public function index()
	{
		return Quiz::filter(request(['search', 'categories']))->with(['users', 'level', 'categories', 'questions', 'questions.answers'])->paginate(9);
	}
}
