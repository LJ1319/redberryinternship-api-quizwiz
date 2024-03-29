<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
	public function verify(string $id, string $hash): JsonResponse
	{
		$away = config('app.frontend_url') . '/login';

		$user = User::find($id);

		abort_if(!$user, 403);
		abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);

		if (!request()->hasValidSignature()) {
			return response()->json('expired', 403);
		}

		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();

			event(new Verified($user));
		}

		return response()->json('verified', 201);
	}
}
