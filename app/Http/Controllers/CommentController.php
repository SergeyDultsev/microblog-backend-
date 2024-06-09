<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CommentController
{
    public function createComment(Request $request, $postId)
    {
        $commentData = $request->all();
        $commentData['user_id'] = Auth::id();
        $commentData['comment_id'] = Uuid::uuid4()->toString();
        $commentData['post_id'] = $postId;
        $commentData['created_at'] = now();

        Comment::create($commentData);
        return response()->json(['message' => 'Comment created successfully'], 201);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        if(!$comment){
            return response()->json(['message' => 'Comment not found'], 404);
        }

        if (Auth::user()->hasRole('admin') || $comment->user_id == Auth::id()) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully']);
        } else {
            return response()->json(['error' => 'You are not authorized to delete this comment'], 403);
        }
    }

    public function getComment($commentId)
    {
        $comment = Comment::find($commentId);
        return new CommentResource($comment);
    }

    public function getComments($postId)
    {
        $post = Post::find($postId);
        $comments = $post->comments()->latest()->get();
        return CommentResource::collection($comments);
    }
}
