<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupTest extends TestCase
{
	use RefreshDatabase;

	public function test_auth_should_return_auth_errors_if_user_is_authenticated()
	{
		$user = User::factory()->create();

		$response = $this->actingAs($user)->postJson(route('signup'));

		$response->assertJson([
			'message' => 'You are already logged in.',
		]);

		$response->assertStatus(401);
	}

	public function test_auth_should_return_errors_if_inputs_are_not_provided()
	{
		$response = $this->postJson(route('signup'));

		$response->assertStatus(422);
	}

	public function test_auth_should_return_username_error_if_username_does_not_contain_at_least_3_characters()
	{
		$response = $this->postJson(route('signup'), [
			'username' => 't',
		]);

		$response->assertJsonValidationErrors([
			'username' => 'The username field must be at least 3 characters.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_username_error_if_username_is_not_unique()
	{
		User::factory()->create([
			'username' => 'test',
		]);

		$response = $this->postJson(route('signup'), [
			'username' => 'test',
		]);

		$response->assertJsonValidationErrors([
			'username' => 'The username has already been taken.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_email_error_if_email_input_is_not_valid_email()
	{
		$response = $this->postJson(route('signup'), [
			'email' => 'testtest.com',
		]);

		$response->assertJsonValidationErrors([
			'email' => 'The email field must be a valid email address.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_email_error_if_email_input_is_not_unique()
	{
		User::factory()->create([
			'email' => 'test@test.com',
		]);

		$response = $this->postJson(route('signup'), [
			'email' => 'test@test.com',
		]);

		$response->assertJsonValidationErrors([
			'email' => 'The email has already been taken.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_password_error_if_password_does_not_contain_at_least_3_characters()
	{
		$response = $this->postJson(route('signup'), [
			'password' => 'p',
		]);

		$response->assertJsonValidationErrors([
			'password' => 'The password field must be at least 3 characters.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_password_error_if_password_is_not_confirmed()
	{
		$response = $this->postJson(route('signup'), [
			'password'              => 'password',
			'password_confirmation' => 'pass',
		]);

		$response->assertJsonValidationErrors([
			'password' => 'The password field confirmation does not match.',
		]);

		$response->assertStatus(422);
	}

	public function test_auth_should_return_message_that_email_verification_link_sent_after_successful_signup()
	{
		$response = $this->postJson(route('signup'), [
			'username'              => 'test100',
			'email'                 => 'test100@test.com',
			'password'              => 'password',
			'password_confirmation' => 'password',
			'terms'                 => true,
		]);

		$response->assertJson([
			'message' => 'User created successfully and email verification sent.',
		]);

		$response->assertStatus(201);
	}
}
