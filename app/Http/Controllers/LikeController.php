<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function toggleLike(Request $request)
    {
        $userId = Auth::id();
        $postId = $request->input('post_id');

        DB::transaction(function () use ($userId, $postId) {
            $like = Like::where('user_id', $userId)
                ->where('post_id', $postId)
                ->first();

            if (!$like) {
                Like::create([
                    'user_id' => $userId,
                    'post_id' => $postId
                ]);

                User::find($postId)->increment('count_like');

                return response()->json(['message' => "You liked it"], 201);
            } else{
                $like->delete();

                User::find($postId)->decrement('count_like');

                return response()->json(['message' => "You deleted your like"]);
            }
        });
    }

    public function getLikesPosts($userId)
    {
        $posts = Like::where('user_id', $userId)->with('post')->get();
        return response()->json($posts);
    }
}
