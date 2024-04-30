<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class SettingController extends Controller
{
	public function index()
	{
		$contact = Setting::where('title', 'contact')->firstOrFail()->data;
		$social = Setting::where('title', 'social')->firstOrFail()->data;

		return response()->json([
			'contact' => $contact,
			'social'  => $social,
		]);
	}
}
