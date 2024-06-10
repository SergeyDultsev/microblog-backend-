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

        if (!$postId) {
            return response()->json(['error' => 'Post ID is required'], 400);
        }

        DB::transaction(function () use ($userId, $postId) {
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

                return response()->json(['message' => "You liked it"]);
            } else{
                $like->delete();

                Post::find($postId)->decrement('count_like');

                return response()->json(['message' => "You deleted your like"]);
            }
        });
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
