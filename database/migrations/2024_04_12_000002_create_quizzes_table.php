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
		Schema::create('quizzes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('level_id')->constrained();
			$table->string('name')->unique();
			$table->text('description');
			$table->string('image');
			$table->time('duration');
			$table->text('instructions');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quizzes');
	}
};
