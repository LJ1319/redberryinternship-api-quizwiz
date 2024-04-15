<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Question extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Question>
	 */
	public static $model = \App\Models\Question::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'body';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'body',
	];

	/**
	 * The relationships that should be eager loaded on index queries.
	 *
	 * @var array
	 */
	public static $with = ['answers'];

	public static function indexQuery(NovaRequest $request, $query)
	{
		return $query->withCount('answers');
	}

	/**
	 * Get the fields displayed by the resource.
	 *
	 * @param \Laravel\Nova\Http\Requests\NovaRequest $request
	 *
	 * @return array
	 */
	public function fields(NovaRequest $request)
	{
		return [
			ID::make()->sortable(),

			Text::make('Question', 'body')->sortable()->rules('required'),

			Number::make('Points')->sortable()->rules('required'),
			Number::make('Total Answers', 'answers_count')->sortable()->onlyOnIndex(),
			Number::make(
				'Correct Answers',
				fn () => $this->answers->map(fn ($answer) => $answer->value)->filter()->count()
			),

			BelongsTo::make('Quiz')->sortable(),
			HasMany::make('Answers'),
		];
	}
}
