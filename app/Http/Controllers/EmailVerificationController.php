<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
	public function verify(string $id, string $hash): JsonResponse
	{
		$user = User::findOrFail($id);

		if (!request()->hasValidSignature()) {
			return response()->json(['message' => 'Email verification expired.'], 403);
		}

		if (!$user->hasVerifiedEmail()) {
			$user->markEmailAsVerified();

			event(new Verified($user));
		}

		return response()->json(['message' => 'Email verified']);
	}
}
