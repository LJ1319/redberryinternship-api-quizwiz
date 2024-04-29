<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'id'              => $this->id,
			'name'            => $this->name,
			'description'     => $this->description,
			'image'           => $this->image,
			'duration'        => $this->duration,
			'instructions'    => $this->instructions,
			'users'           => $this->users,
			'level'           => $this->level,
			'categories'      => $this->categories,
			'questions'       => QuestionResource::collection($this->questions),
			'total_users'     => $this->totalUsers(),
			'questions_count' => $this->questions->count(),
		];
	}
}
