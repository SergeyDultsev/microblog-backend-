<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(PostRequest $request)
    {
        // Добавляем user_id к данным запроса
        $postData = $request->all();
        $postData['user_id'] = Auth::id();

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

    public function getPost(Post $post)
    {
        return response()->json($post);
    }

    public function getUserPosts($user)
    {
        $posts = $user->posts()->pluck('id');
        return response()->json($posts);
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

        return response()->json($posts);
    }
}
