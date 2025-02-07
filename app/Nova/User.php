<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\User>
	 */
	public static string $model = \App\Models\User::class;

	/**
	 * The single value that should be used to represent the resource when being displayed.
	 *
	 * @var string
	 */
	public static $title = 'username';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id', 'username', 'email',
	];

	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		return $query->withCount('quizzes');
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

			Avatar::make('Avatar')->squared(),

			Text::make('Username')
				->sortable()
				->rules('required', 'max:255'),

			Text::make('Email')
				->sortable()
				->rules('required', 'email', 'max:254')
				->creationRules('unique:users,email')
				->updateRules('unique:users,email,{{resourceId}}'),

			Password::make('Password')
				->onlyOnForms()
				->creationRules('required', Rules\Password::defaults())
				->updateRules('nullable', Rules\Password::defaults()),

			Number::make('Total Quizzes', 'quizzes_count')->sortable()->onlyOnIndex(),

			BelongsToMany::make('Quizzes'),
		];
	}
}
