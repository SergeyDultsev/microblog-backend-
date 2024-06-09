<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public static $wrap = null; // Отключаю обёртку data
    public function toArray(Request $request): array
    {

        return [
            'comment_id' => $this->comment_id,
            'full_name' => $this->user ? $this->user->full_name : null,
            'comment_content' => $this->comment_content,
            'avatar' => new AvatarResource($this->avatar),
            'created_at' => $this->created_at,
        ];
    }
}
