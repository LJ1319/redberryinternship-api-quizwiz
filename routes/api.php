<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SanctumController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
	->controller(SanctumController::class)->group(function () {
		Route::get('/user', 'get');
	});

Route::controller(AuthController::class)->group(function () {
	Route::post('/signup', 'signup');
	Route::post('/login', 'login');
});
