<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'link' => $this->link,
            'title' => $this->title,
            'youtube_uid' => $this->youtube_uid,
            'thumbnail' => getImageUrl($this->thumbnail)
        ];
    }
}
