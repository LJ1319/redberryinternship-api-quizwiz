<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
	public function verify(string $id): JsonResponse
	{
		$user = User::findOrFail($id);

		if ($user->hasVerifiedEmail()) {
			return response()->json(['message' => 'Your email is already verified.'], 403);
		}

		$user->markEmailAsVerified();
		event(new Verified($user));

		return response()->json(['message' => 'Email verified.']);
	}

	public function resend(EmailVerificationRequest $request): JsonResponse
	{
		$credentials = $request->validated();

		$user = User::whereEmail($credentials['email'])->first();

		if (!$user) {
			return response()->json(['message' => 'There is no account associated with this email. Please sign up first.'], 422);
		}

		if (isset($user->email_verified_at)) {
			return response()->json(['message' => 'Your email is already verified. Go back to login.'], 403);
		}

		$user->sendEmailVerificationNotification();

		return response()->json(['message' => 'Email verification resent.']);
	}
}
