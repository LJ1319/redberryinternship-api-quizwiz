<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
	Route::controller(AuthController::class)->group(function () {
		Route::post('/signup', 'signup')->name('signup');
		Route::post('/login', 'login')->name('login');
	});

	Route::group(
		['controller' => EmailVerificationController::class, 'prefix' => 'email', 'as' => 'verification.'],
		function () {
			Route::get('/verify/{id}/{hash}', 'verify')->middleware('signed')->name('verify');
			Route::post('/verification-notification', 'resend')->middleware('throttle:6,1')->name('send');
		}
	);

	Route::group(
		['controller' => PasswordResetController::class, 'as' => 'password.'],
		function () {
			Route::post('/forgot-password', 'forgot')->name('email');
			Route::post('/reset-password', 'reset')->name('reset');
		}
	);
});

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'get']);
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['controller' => QuizController::class, 'as' => 'quizzes.'], function () {
	Route::get('quizzes', 'index')->name('index');
	Route::get('quizzes/{id}', 'get')->name('get');
	Route::get('quizzes/{id}/similar', 'getSimilar')->name('similar');
	Route::post('quizzes/{id}/submit', 'submit')->name('submit');
});

Route::get('levels', [LevelController::class, 'index'])->name('levels');
Route::get('categories', [CategoryController::class, 'index'])->name('categories');
Route::get('settings', [SettingController::class, 'index'])->name('settings');
