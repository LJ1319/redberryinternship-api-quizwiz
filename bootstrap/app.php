<?php

use App\Http\Middleware\AbortIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		$middleware->statefulApi();
		$middleware->alias([
			'guest' => AbortIfAuthenticated::class,
		]);
	})
	->withExceptions(function (Exceptions $exceptions) {
		$exceptions->render(function (InvalidSignatureException $e) {
			return response()->json(['message' => 'Invalid email verification link.'], 403);
		});
	})->create();
