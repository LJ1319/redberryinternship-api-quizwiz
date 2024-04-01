<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/user', [UserController::class, 'get']);
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::controller(AuthController::class)->group(function () {
	Route::middleware('guest')->group(function () {
		Route::post('/signup', 'signup')->name('signup');
		Route::post('/login', 'login')->name('login');
	});
});

Route::controller(EmailVerificationController::class)->group(function () {
	Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
	Route::post('/email/verification-notification', 'resend')->middleware('throttle:6,1')->name('verification.send');
});
