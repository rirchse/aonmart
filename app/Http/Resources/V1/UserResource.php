<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "access_token" => $this->whenAppended('access_token', $this->access_token),
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "image" => getImageUrl($this->image),
            "cover_image" => getImageUrl($this->cover_image),
            "mobile" => $this->mobile,
            "phone" => $this->phone,
            "about" => $this->about,
            "status" => $this->status,
            "balance" => $this->balance,
            "created_at" => dateFormat($this->created_at)
        ];
    }
}
