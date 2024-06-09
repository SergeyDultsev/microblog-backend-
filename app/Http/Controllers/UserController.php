<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function getUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    public function userAbout(Request $request, User $userId)
    {
        $user = User::find($userId);

        if (!$request->has('about')) {
            return response()->json(['error' => 'about parameter is required'], 400);
        }

        $user->save();
    }

    public function userAvatar(Request $request, User $userId)
    {
        $user = User::find($userId->id);

        if (!$request->has('profile_avatar_url')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }
    }

    public function userHeadAvatar(Request $request, User $userId)
    {
        $user = User::find($userId);

        if (!$request->has('profile_head_avatar_url')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }
    }

    public function deleteUser()
    {
        $user = Auth::user();

        if ($user) {
            $user->delete();
            $user->tokens()->delete();
            return response()->json(['message' => 'User deleted successfully']);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function searchUser(Request $request)
    {
        $query = $request->query('query');

        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('surname', 'LIKE', "%{$query}%")
            ->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'Users not found'], 404);
        }

        return response()->json($users);
    }
}
