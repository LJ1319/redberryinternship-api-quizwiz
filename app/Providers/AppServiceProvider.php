<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		Model::preventLazyLoading();
		JsonResource::withoutWrapping();

		$this->verifyEmail();
		$this->resetPassword();
	}

	protected function verifyEmail(): void
	{
		VerifyEmail::toMailUsing(function ($notifiable) {
			$user = User::whereEmail($notifiable->getEmailForVerification())->first();

			$away = config('app.frontend_url') . '/login';

			$name = 'verification.verify';
			$expiration = now()->addMinutes(Config::get('auth.verification.expire', 120));
			$id = $notifiable->getKey();
			$hash = sha1($notifiable->getEmailForVerification());

			$verificationUrl = URL::temporarySignedRoute(
				$name,
				$expiration,
				[
					'id'   => $id,
					'hash' => $hash,
				]
			);

			return (new MailMessage)
				->subject('Email verification required!')
				->markdown('mail.email-verification', [
					'username'         => $user->username,
					'away'             => $away,
					'expiration'       => $expiration,
					'verificationUrl'  => $verificationUrl,
				]);
		});
	}

	protected function resetPassword(): void
	{
		ResetPassword::toMailUsing(function (User $user, string $token) {
			$away = config('app.frontend_url') . '/reset';

			$name = 'password.reset';
			$expiration = now()->addMinutes(Config::get('auth.passwords.users.expire', 120));
			$email = $user->getEmailForPasswordReset();

			$resetUrl = URL::temporarySignedRoute(
				$name,
				$expiration,
			);

			return (new MailMessage)
				->subject('Password reset requested!')
				->markdown('mail.password-reset', [
					'username'     => $user->username,
					'away'         => $away,
					'expiration'   => $expiration,
					'resetUrl'     => $resetUrl,
					'token'        => $token,
					'email'        => $email,
				]);
		});
	}
}
