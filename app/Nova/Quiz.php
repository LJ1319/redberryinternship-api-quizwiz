<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quiz extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Quiz>
	 */
	public static string $model = \App\Models\Quiz::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'name';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'name',
	];

	/**
	 * The relationships that should be eager loaded on index queries.
	 *
	 * @var array
	 */
	public static $with = ['questions'];

	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		return $query->withCount('users', 'categories', 'questions');
	}

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return array
	 */
	public function fields(NovaRequest $request): array
	{
		return [
			ID::make()->sortable(),

			Image::make('Image')->squared()->rules('required'),

			Text::make('Name')->showWhenPeeking()->sortable()->rules('required', 'max:255'),
			Textarea::make('Description')->showWhenPeeking()->rules('required'),
			Textarea::make('Instructions')->showWhenPeeking()->rules('required'),

			Number::make('Total Categories', 'categories_count')->sortable()->onlyOnIndex(),
			Number::make('Total Questions', 'questions_count')->sortable()->onlyOnIndex(),
			Number::make(
				'Points',
				fn () => $this->questions->map(fn ($question) => $question->points)->sum()
			)->sortable(),
			Number::make('Total Users', 'users_count')->sortable()->onlyOnIndex(),
			Number::make('Duration')->sortable()->rules('required'),

			HasMany::make('Questions')->sortable(),
			BelongsTo::make('Level')->sortable(),
			BelongsToMany::make('Categories'),
			BelongsToMany::make('Users')->fields(fn () => [
				DateTime::make('Completed At', 'created_at')->sortable(),
				Text::make('Time')->sortable(),
				Number::make('Score')->sortable(),
			]),
		];
	}
}
