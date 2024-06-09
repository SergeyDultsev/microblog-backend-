<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public static $wrap = null; // Отключаю обёртку data
    public function toArray($request)
    {
        $filename = basename($this->media_content);

        return [
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'text_content' => $this->text_content,
            'media_content' => $filename,
            'hasLiked' => $this->hasLiked,
            'count_like' => $this->count_like,
            'count_comment' => $this->count_comment,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
