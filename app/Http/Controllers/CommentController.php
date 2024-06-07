<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function createComment(Request $request)
    {
        $commentData = $request->all();
        $commentData['user_id'] = Auth::id();

        Comment::create($commentData);
        return response()->json(['message' => 'Comment created successfully'], 201);
    }

    public function deleteComment(Comment $comment)
    {
        if (Auth::user()->isAdmin() || $comment->user_id == Auth::id()) {
            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully'], 204);
        } else {
            return response()->json(['error' => 'You are not authorized to delete this comment'], 403);
        }
    }

    public function getComment(Comment $comment)
    {
        return response()->json($comment);
    }

    public function getComments($postId)
    {
        $comments = Post::findOrFail($postId)->comments()->pluck('id');
        return response()->json($comments);
    }
}
