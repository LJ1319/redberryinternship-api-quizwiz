<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
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
		VerifyEmail::toMailUsing(function ($notifiable) {
			$user = User::whereEmail($notifiable->getEmailForVerification())->first();

			$away = config('app.frontend_url') . '/login';

			$name = 'verification.verify';
			$expiration = Carbon::now()->addMinutes(Config::get('auth.verification.expire', 120));
			$id = $notifiable->getKey();
			$hash = sha1($notifiable->getEmailForVerification());

			$verifyUrl = URL::temporarySignedRoute(
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
					'username'   => $user->username,
					'away'       => $away,
					'verifyUrl'  => $verifyUrl,
				]);
		});
	}
}
