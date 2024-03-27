<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\SignupUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function signup(LoginUserRequest $request)
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		return response()->json($user, 201);
	}

	public function login(SignupUserRequest $request)
	{
		$credentials = $request->validated();

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();
		}

		return response()->json('logged in', 201);
	}

	public function logout()
	{
	}
}
