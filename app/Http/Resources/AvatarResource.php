<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'avatar_url' => $this->avatar_url,
            'head_avatar_url' => $this->head_avatar_url,
        ];
    }
}
