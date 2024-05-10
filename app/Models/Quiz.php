<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)->withPivot('time', 'score')->withTimestamps();
	}

	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class)->withTimestamps();
	}

	public function questions(): HasMany
	{
		return $this->hasMany(Question::class);
	}

	public function scopeFilter($query, array $filters): void
	{
		$query->when($filters['search'] ?? null, function ($query, $search) {
			$query->where('name', 'like', '%' . $search . '%');
		});

		$query->when(isset($filters['filter']) && $filters['filter'] === 'completed' ?? null, function ($query) {
			$query->whereHas('users', function ($query) {
				$query->where('user_id', auth()->id());
			});
		});

		$query->when(isset($filters['filter']) && $filters['filter'] === 'not_completed' ?? null, function ($query) {
			$query->whereDoesntHave('users', function ($query) {
				$query->where('user_id', auth()->id());
			});
		});

		$query->when($filters['levels'] ?? null, function ($query, $levels) {
			$query->whereHas('level', function ($query) use ($levels) {
				$query->whereIn('id', $levels);
			});
		});

		$query->when($filters['categories'] ?? null, function ($query, $categories) {
			$query->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('categories.id', $categories);
			});
		});
	}

	public function totalUsers(): int
	{
		return DB::table('quiz_user')
				->where('quiz_id', $this->id)
				->count();
	}
}
