<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FilteringSortingTest extends TestCase
{
	use RefreshDatabase;

	protected function setUp(): void
	{
		parent::setUp();

		$levels = config('levels');
		DB::table('levels')->insert($levels);
	}

	public function test_should_return_quizzes_filtered_by_search_query()
	{
		Quiz::factory()->create(['name' => 'test one']);
		Quiz::factory()->create(['name' => 'test two']);
		Quiz::factory()->create(['name' => 'quiz one']);
		Quiz::factory()->create(['name' => 'quiz two']);

		$response = $this->getJson(route('quizzes.index', [
			'search' => 'one',
		]));

		$response->assertOk();
		$response->assertJsonCount(2, 'data');
	}

	public function test_should_return_completed_quizzes_of_authenticated_user()
	{
		$user = User::factory()->create();

		Quiz::factory(2)->hasAttached($user, ['time' => 1])->create();
		Quiz::factory(2)->hasAttached(User::factory(), ['time' => 1])->create();

		$response = $this->actingAs($user)->getJson(route('quizzes.index', [
			'filter' => 'completed',
		]));

		$response->assertOk();
		$response->assertJsonCount(2, 'data');
	}

	public function test_should_return_not_completed_quizzes_of_authenticated_users()
	{
		$user = User::factory()->create();

		Quiz::factory(2)->hasAttached($user, ['time' => 1])->create();
		Quiz::factory(2)->hasAttached(User::factory(), ['time' => 1])->create();

		$response = $this->actingAs($user)->getJson(route('quizzes.index', [
			'filter' => 'not_completed',
		]));

		$response->assertOk();
		$response->assertJsonCount(2, 'data');
	}

	public function test_should_return_quizzes_filtered_by_levels()
	{
		Quiz::factory(2)->create(['level_id' => 1]);
		Quiz::factory()->create(['level_id' => 2]);
		Quiz::factory()->create(['level_id' => 3]);

		$response = $this->getJson(route('quizzes.index', [
			'levels' => ['1', '2'],
		]));

		$response->assertOk();
		$response->assertJsonCount(3, 'data');
	}

	public function test_should_return_quizzes_filtered_by_categories()
	{
		Quiz::factory()->hasAttached(Category::factory(1, ['id' => 1]))->create();
		Quiz::factory()->hasAttached(Category::factory(1, ['id' => 2]))->create();
		Quiz::factory()->hasAttached(Category::factory(1, ['id' => 3]))->create();

		$response = $this->getJson(route('quizzes.index', [
			'categories' => ['1', '2'],
		]));

		$response->assertOk();
		$response->assertJsonCount(2, 'data');
	}

	public function test_should_return_quizzes_sorted_by_newest()
	{
		Quiz::factory()->create(['name' => 'first', 'created_at' => now()->subMonth()]);
		Quiz::factory()->create(['name' => 'second', 'created_at' => now()]);

		$response = $this->getJson(route('quizzes.index', [
			'order_column'    => 'created_at',
			'order_direction' => 'desc',
		]));

		$response->assertOk();
		$response->assertSeeInOrder(['second', 'first']);
	}

	public function test_should_return_quizzes_sorted_by_oldest()
	{
		Quiz::factory()->create(['name' => 'first', 'created_at' => now()->subMonth()]);
		Quiz::factory()->create(['name' => 'second', 'created_at' => now()]);

		$response = $this->getJson(route('quizzes.index', [
			'order_column'    => 'created_at',
			'order_direction' => 'asc',
		]));

		$response->assertOk();
		$response->assertSeeInOrder(['first', 'second']);
	}

	public function test_should_return_quizzes_sorted_by_a_to_z()
	{
		Quiz::factory()->create(['name' => 'quiz a']);
		Quiz::factory()->create(['name' => 'quiz z']);

		$response = $this->getJson(route('quizzes.index', [
			'order_column'    => 'name',
			'order_direction' => 'asc',
		]));

		$response->assertOk();
		$response->assertSeeInOrder(['quiz a', 'quiz z']);
	}

	public function test_should_return_quizzes_sorted_by_z_to_a()
	{
		Quiz::factory()->create(['name' => 'quiz a']);
		Quiz::factory()->create(['name' => 'quiz z']);

		$response = $this->getJson(route('quizzes.index', [
			'order_column'    => 'name',
			'order_direction' => 'desc',
		]));

		$response->assertOk();
		$response->assertSeeInOrder(['quiz z', 'quiz a']);
	}

	public function test_should_return_quizzes_sorted_by_popularity()
	{
		Quiz::factory()->hasAttached(User::factory(1), ['time' => 1])->create(['name' => 'quiz a']);
		Quiz::factory()->hasAttached(User::factory(2), ['time' => 2])->create(['name' => 'quiz z']);

		$response = $this->getJson(route('quizzes.index', [
			'order_column'    => 'users_count',
			'order_direction' => 'desc',
		]));

		$response->assertOk();
		$response->assertSeeInOrder(['quiz z', 'quiz a']);
	}
}
