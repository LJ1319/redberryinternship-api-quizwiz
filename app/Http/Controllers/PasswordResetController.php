<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordForgotRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
	public function forgot(PasswordForgotRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$status = Password::sendResetLink($credentials);

		return $status === Password::RESET_LINK_SENT
			? response()->json(['message' => __($status)])
			: response()->json(['message' => __($status)], 422);
	}

	public function reset(PasswordUpdateRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		if (!request()->hasValidSignature()) {
			return response()->json(['message' => 'Invalid password reset link.'], 403);
		}

		$status = Password::reset(
			$credentials,
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		return $status === Password::PASSWORD_RESET
			? response()->json(['message' => __($status)])
			: response()->json(['message' => __($status)], 422);
	}
}
