<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ImageResource extends JsonResource
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
            'name' => $this->file_name,
            'mime' => $this->mime,
            'size' => $this->size,
            // 'url' => url('/api/images/' . $this->id)
            'url' => URL::temporarySignedRoute(
                'getImage',
                now()->addMinutes(10),
                ['image' => $this]
            )
        ];
    }
}
