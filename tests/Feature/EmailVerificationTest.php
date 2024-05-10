<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
	use RefreshDatabase;

	public function test_email_verification_should_assert_email_verification_sent()
	{
		Notification::fake();

		$userData = [
			'username'              => 'test100',
			'email'                 => 'test100@test.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
			'terms'                 => true,
		];

		$response = $this->postJson(route('signup'), $userData);
		$response->assertStatus(201);

		$user = User::firstWhere('email', $userData['email']);

		Notification::assertSentTo(
			$user,
			VerifyEmail::class
		);
	}

	public function test_email_verification_should_test_user_has_verified_email()
	{
		$user = User::factory()->create(['email_verified_at' => null]);

		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(Config::get('auth.verification.expire', 120)),
			['id' => $user->id, 'hash' => sha1($user->email)]
		);

		$response = $this->get($verificationUrl);
		$response->assertJson(['message' => 'Email verified.']);

		$this->assertNotNull($user->fresh()->email_verified_at);
	}

	public function test_email_verification_should_return_error_message_if_email_is_already_verified()
	{
		$user = User::factory()->create();

		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(Config::get('auth.verification.expire', 120)),
			['id' => $user->id, 'hash' => sha1($user->email)]
		);

		$response = $this->get($verificationUrl);

		$response->assertJson(['message' => 'Your email is already verified.']);
		$response->assertStatus(403);
	}

	public function test_email_verification_should_return_email_error_if_email_input_is_not_provided()
	{
		$response = $this->postJson(route('verification.send'));

		$response->assertStatus(422);
	}

	public function test_email_verification_should_return_email_error_if_email_input_is_not_valid_email()
	{
		$response = $this->postJson(route('verification.send'), [
			'email' => 'testtest.com',
		]);

		$response->assertJsonValidationErrors([
			'email' => 'The email field must be a valid email address.',
		]);

		$response->assertStatus(422);
	}

	public function test_email_resend_should_return_error_message_if_email_is_already_verified()
	{
		$email = 'test@test.com';
		User::factory()->create(['email' => $email]);

		$response = $this->postJson(route('verification.send'), [
			'email' => $email,
		]);

		$response->assertJson(['message' => 'Your email is already verified. Go back to login.']);

		$response->assertStatus(403);
	}

	public function test_email_resend_should_return_error_message_if_user_account_does_not_exist()
	{
		$response = $this->postJson(route('verification.send'), [
			'email' => 'test@test.com',
		]);

		$response->assertJson(['message' => 'There is no account associated with this email. Please sign up first.']);

		$response->assertStatus(422);
	}

	public function test_email_resend_should_return_message_that_email_verification_resent()
	{
		$email = 'test1000@test.com';
		User::factory()->create(['email' => $email, 'email_verified_at' => null]);

		$response = $this->postJson(route('verification.send'), [
			'email' => $email,
		]);

		$response->assertJson(['message' => 'Email verification resent.']);
	}
}
