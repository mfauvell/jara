<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
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
            'description' => $this->description,
            'time' => $this->time,
            'user' => new UserResource($this->user),
            'visibility' => new VisibilityResource($this->visibility),
            'images' => ImageResource::collection($this->images),
            'steps' => StepResource::collection($this->steps)
        ];
    }
}
