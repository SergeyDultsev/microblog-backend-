<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
use function Webmozart\Assert\Tests\StaticAnalysis\uuid;
use App\Http\Resources\PostResource;

class PostController
{
    public function createPost(PostRequest $request)
    {
        $postData = $request->validated();
        $postData['post_id'] = Uuid::uuid4()->toString();
        $postData['user_id'] = Auth::id();
        $postData['created_at'] = now();

        // Обработка изображений
        if ($request->hasFile('media_content')) {
            $file = $request->file('media_content');
            $path = $file->store('public/images');
            $postData['media_content'] = $path;
        }

        Post::create($postData);
        return response()->json(['message' => 'Successful create post'], 201);
    }

    public function updatePost(PostRequest $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'You are not authorized to update this post'], 403);
        }

        $requestData = $request->only(['text_content', 'media_content']);

        if(isset($requestData['text_content'])) {
            $editDataPost['text_content'] = $requestData['text_content'];
        }

        // Обработка изображений
        if ($request->hasFile('media_content')) {
            $file = $request->file('media_content');
            $path = $file->store('storage/app/public/images');
            $editDataPost['media_content'] = $path;
        }

        $post->update($editDataPost);
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

        $filename = basename($post->media_content);

        return response()->json([
            'post_id' => $post->post_id,
            'user_id' => $post->user_id,
            'text_content' => $post->text_content,
            'filename' => $filename,
            'count_like' => $post->count_like,
            'count_comment' => $post->count_comment,
            'created_at' => $post->created_at,
        ]);
    }

    public function getUserPosts($userId)
    {
        $user = User::find($userId);
        $posts = $user->posts;
        return response()->json($posts);
    }

    // Лента постов
    public function feed()
    {
        if(Auth::check()) {
            $user = Auth::id();
            $subscriptionUsers = Subscription::where('subscriber_id', $user)->pluck('target_id');
            $posts = Post::whereIn('user_id', $subscriptionUsers)->latest()->paginate(100);

            foreach ($posts as $post) {
                $post->hasLiked = $post->likes()->where('user_id', $user)->exists();
            }

            return response()->json($posts);
        } else{
            $posts = Post::latest()->paginate(100);
        }

        return PostResource::Collection($posts);
    }
}
