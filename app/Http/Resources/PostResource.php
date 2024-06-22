<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $filename = basename($this->media_content);

        return [
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'full_name' => $this->user ? $this->user->full_name : null,
            'text_content' => $this->text_content,
            'media_content' => $filename,
            'hasLiked' => $this->hasLiked,
            'count_like' => $this->count_like,
            'count_comment' => $this->count_comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
