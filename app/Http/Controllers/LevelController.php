<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Database\Eloquent\Collection;

class LevelController extends Controller
{
	public function index(): Collection
	{
		return Level::all();
	}
}
