<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanctumController extends Controller
{
	public function get(Request $request)
	{
		return $request->user();
	}
}
