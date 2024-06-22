<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;
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
            $path = $file->store('public/posts-media-content');
            $postData['media_content'] = $path;
        }

        Post::create($postData);
        return response()->json(['message' => 'Successful create post'], 201);
    }

    public function updatePost(PostRequest $request, Post $post)
    {
        $user = Auth::user();

        // Проверка, что пользователь авторизован для обновления поста
        if ($post->user_id !== $user->id) {
            return response()->json(['error' => 'You are not authorized to update this post'], 403);
        }

        // Получение валидированных данных
        $requestData = $request->validated();

        // Обновление текстового контента, если он есть
        if(isset($requestData['text_content'])) {
            $post->text_content = $requestData['text_content'];
        }

        // Обработка изображений
        if ($request->hasFile('media_content')) {
            $file = $request->file('media_content');
            $path = $file->store('/public/images');
            $post->media_content = str_replace('public/', 'storage/', $path);
        }

        // Сохранение изменений
        $post->save();
        return response()->json(['message' => 'Successful edit post']);
    }

    public function deletePost($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        if (Auth::user()->hasRole('admin') || $post->user_id === Auth::id()) {
            $post->delete();
            return response()->json(['message' => 'Post deleted successfully']);
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

        return new PostResource($post);
    }

    public function getUserPosts($userId)
    {
        $user = User::find($userId);
        $posts = $user->posts;

        return PostResource::Collection($posts);
    }

    public function feed()
    {
        $feedPosts = Post::latest()->paginate(100);
        return PostResource::Collection($feedPosts);
    }
}
