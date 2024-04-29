<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('quiz_user', function (Blueprint $table) {
			$table->unsignedSmallInteger('time')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('quiz_user', function (Blueprint $table) {
			$table->time('time')->change();
		});
	}
};
