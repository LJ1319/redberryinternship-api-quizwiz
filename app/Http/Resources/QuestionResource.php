<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'                  => $this->id,
			'body'                => $this->body,
			'points'              => $this->points,
			'answers'             => $this->answers,
			'correct_answers'     => $this->answers->where('value', true)->count(),
		];
	}
}
