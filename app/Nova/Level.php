<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Level extends Resource
{
	/**
	 * The model the resource corresponds to.
	 *
	 * @var class-string<\App\Models\Level>
	 */
	public static string $model = \App\Models\Level::class;

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

			Text::make('Name')->sortable()->rules('required'),
		];
	}
}
