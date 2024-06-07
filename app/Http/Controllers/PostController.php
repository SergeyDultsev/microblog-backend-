<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use function Webmozart\Assert\Tests\StaticAnalysis\uuid;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function createPost(PostRequest $request)
    {
        $postData = $request->validated();
        $postData['post_id'] = Uuid::uuid4()->toString();
        $postData['user_id'] = Auth::id();
        $postData['created_at'] = now();

        Post::create($postData);
        return response()->json(['message' => 'Successful create post'], 201);
    }

    public function updatePost(PostRequest $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to update this post'], 403);
        }

        $postData = $request->only(['text_content', 'media_content']);
        $post->update($postData);
        return response()->json(['message' => 'Successful edit post']);
    }

    public function deletePost(Post $post)
    {
        if (Auth::user()->isAdmin() || $post->user_id === Auth::id()) {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully'], 204);
        } else {
            return response()->json(['error' => 'You are not authorized to delete this post'], 403);
        }
    }

    public function getPost($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['data' => $post]);
    }

    public function getUserPosts($userId)
    {
        $user = User::find($userId);
        $posts = $user->posts;
        return response()->json(['data' => $posts]);
    }

    // Лента постов
    public function feed()
    {
        if(Auth::check()) {
            $user = Auth::id();
            $subscriptionUsers = Subscription::where('subscriber_id', $user)->pluck('target_id');
            $posts = Post::whereIn('user_id', $subscriptionUsers)->latest()->paginate(100);
            return response()->json($posts);
        } else{
            $posts = Post::latest()->paginate(100);
        }

        return PostResource::Collection($posts);
    }
}
