<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
	->controller(UserController::class)->group(function () {
		Route::get('/user', 'get');
	});

Route::controller(AuthController::class)->group(function () {
	Route::post('/signup', 'signup');
	Route::post('/login', 'login');
});
