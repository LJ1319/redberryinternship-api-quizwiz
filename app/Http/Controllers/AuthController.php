<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\SignupUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function signup(SignupUserRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		event(new Registered($user));

		return response()->json(['message' => 'Email verification sent.'], 201);
	}

	public function login(LoginUserRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		if (Auth::attempt($credentials)) {
			$request->session()->regenerate();
		} else {
			return response()->json('The provided credentials do not match our records.', 422);
		}

		$user = User::whereEmail($credentials['email'])->first();

		if (!isset($user->email_verified_at)) {
			return response()->json('Your email is not verified.', 422);
		}

		return response()->json('Successfully logged in.', 201);
	}

	public function logout()
	{
	}
}
