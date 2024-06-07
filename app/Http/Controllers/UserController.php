<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['data' => $user]);
    }

    public function updateRole(Request $request, User $userId)
    {
        // Имеет ли текущий пользователь право изменять роль
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($userId->id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$request->has('role')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }

        // Переданное значение роли допустимо (0 или 1)
        $role = $request->input('role');
        if (!in_array($role, [0, 1])) {
            return response()->json(['error' => 'Invalid role value'], 400);
        }

        $user->role = $role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully']);
    }

    public function updateUserAbout(Request $request, User $userId)
    {
        $user = User::find($userId);

        // Наличие параметра роли
        if (!$request->has('about')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }

        $user->save();
    }

    public function updateUserAvatar(Request $request, User $userId)
    {
        $user = User::find($userId->id);

        if (!$request->has('profile_avatar_url')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }
    }

    public function updateUserHeadAvatar(Request $request, User $userId)
    {
        $user = User::find($userId);

        if (!$request->has('profile_head_avatar_url')) {
            return response()->json(['error' => 'Role parameter is required'], 400);
        }
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            Auth::logout();
            return response()->json(['message' => 'User deleted successfully']);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function searchUser(Request $request)
    {
        $query = $request->query('query');


        if (!$query) {
            return response()->json(['error' => 'Параметр запроса обязателен'], 400);
        }

        // Фильтруем пользователей по имени и фамилии
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('surname', 'LIKE', "%{$query}%")
            ->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'Пользователи не найдены'], 404);
        }

        return response()->json(['data' => $users]);
    }
}
