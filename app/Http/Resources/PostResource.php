<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'text_content' => $this->text_content,
            'media_content' => $this->media_content,
            'hasLiked' => $this->hasLiked,
            'count_like' => $this->count_like,
            'count_comment' => $this->count_comment,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
