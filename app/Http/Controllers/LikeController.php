<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LikeController
{
    public function toggleLike($postId)
    {
        $userId = Auth::id();

        $responseMessage = '';
        $statusCode = 200;

        DB::transaction(function () use ($userId, $postId, &$responseMessage, &$statusCode) {
            $like = Like::where('user_id', $userId)
                ->where('post_id', $postId)
                ->first();

            if (!$like) {
                Like::create([
                    'like_id' => Uuid::uuid4()->toString(),
                    'post_id' => $postId,
                    'user_id' => $userId,
                ]);

                Post::find($postId)->increment('count_like');

                $responseMessage = 'You liked it';
                $statusCode = 201;
            } else{
                $like->delete();

                Post::find($postId)->decrement('count_like');

                $responseMessage = 'You deleted your like';
                $statusCode = 200;
            }
        });

        return response()->json(['message' => $responseMessage], $statusCode);
    }

    public function getLikesPosts()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        $likesPosts = Like::where('user_id', $user->id)->get();

        if ($likesPosts->isEmpty()) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $posts = $likesPosts->map(function ($like) {
            return $like->post;
        })->filter();

        $postIds = $posts->pluck('post_id')->toArray();

        $posts = Post::whereIn('post_id', array_map('strval', $postIds))->get();

        return PostResource::collection($posts);
    }
}
