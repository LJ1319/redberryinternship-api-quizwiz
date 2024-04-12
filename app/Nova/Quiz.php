<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
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
	public static $title = 'id';

	/**
	 * The columns that should be searched.
	 *
	 * @var array
	 */
	public static $search = [
		'id',
	];

	public static function indexQuery(NovaRequest $request, $query): Builder
	{
		return $query->withCount('users');
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

			Image::make('Image', null, 'local')->squared(),

			Text::make('Name')->sortable()->rules('required', 'max:255'),
			Trix::make('Description')->rules('required'),
			Trix::make('Instructions')->rules('required'),

			Number::make('Points')->sortable()->rules('required'),
			Number::make('Duration')->sortable()->rules('required'),
			Number::make('Total Users', 'users_count')->sortable(),

			BelongsToMany::make('Users'),
		];
	}
}
