<?php

use Carbon\Carbon;

return [
	[
		'title' => 'contact',
		'data'  => json_encode([
			'email'      => 'quizwiz@gmail.com',
			'tel'        => '+995 328989',
		]),
		'created_at' => Carbon::now(),
		'updated_at' => Carbon::now(),
	],
	[
		'title' => 'social',
		'data'  => json_encode([
			'facebook'   => 'https://www.facebook.com',
			'linkedin'   => 'https://www.linkedin.com',
		]),
		'created_at' => Carbon::now(),
		'updated_at' => Carbon::now(),
	],
];
