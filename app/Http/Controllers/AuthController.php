<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\SignupUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function signup(SignupUserRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::create($credentials);

		event(new Registered($user));

		return response()->json(['message' => 'User created successfully and email verification sent.'], 201);
	}

	public function login(LoginUserRequest $request): JsonResponse
	{
		abort_if(Auth::check(), 401, 'You are already logged in.');

		$credentials = $request->validated();
		$email = $credentials['email'];
		$password = $credentials['password'];
		$remember = $credentials['remember'];

		if (!Auth::attempt([
			'email'             => $email,
			'password'          => $password,
			fn (Builder $query) => $query->whereNotNull('email_verified_at'),
		], $remember)) {
			return response()->json(['message' => 'Authentication failed. Check your credentials and email verification.'], 422);
		}

		$request->session()->regenerate();

		return response()->json(['message' => 'Successfully logged in.']);
	}

	public function logout(Request $request): JsonResponse
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json(['message' => 'Successfully logged out.']);
	}
}
