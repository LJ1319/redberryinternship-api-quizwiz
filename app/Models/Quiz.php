<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class)->withPivot('completed_at', 'time', 'score')->withTimestamps();
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
			$query->where('name', 'like', $search . '%');
		});

		$query->when($filters['categories'] ?? null, function ($query, $categories) {
			$query->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('categories.id', $categories);
			});
		});
	}
}
