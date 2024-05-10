<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SimilarQuizzesTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();

		$levels = config('levels');
		DB::table('levels')->insert($levels);
	}

	public function test_should_return_three_quizzes_for_guest_user()
	{
		$target = Quiz::factory()->hasAttached(Category::factory(3))->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 3])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 4])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 2])->create();

		$response = $this->getJson(route('quizzes.similar', $target->id));

		$response->assertOk();
		$response->assertJsonCount(3);
	}

	public function test_should_return_three_quizzes_not_completed_by_auth_user()
	{
		$user = User::factory()->create();

		$target = Quiz::factory()->hasAttached(Category::factory(3))->hasAttached($user, ['time' => 1])->create();

		Quiz::factory()->hasAttached(Category::factory(3))->hasAttached($user, ['time' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(3))->hasAttached($user, ['time' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(3))->hasAttached($user, ['time' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 3])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 4])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 1])->create();
		Quiz::factory()->hasAttached(Category::factory(), ['category_id' => 2])->create();

		$response = $this->actingAs($user)->getJson(route('quizzes.similar', $target->id));

		$response->assertOk();
		$response->assertJsonCount(3);
	}
}
