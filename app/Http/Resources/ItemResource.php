<?php

namespace App\Http\Resources;

use App\Http\Resources\ItemsPhotoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return [
			'id' => $this->id,
			'title' => $this->title,
			'content' => $this->content,
			'category' => $this->category,
			'price' => $this->price,
			'popular' => $this->popular,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
			'user_id' => $this->user_id,
			'type_id' => $this->type_id,
			'photos' => $this->photos
		];
    }
}
