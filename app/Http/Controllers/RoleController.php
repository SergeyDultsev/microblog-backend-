<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleController
{
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


        $user->role = $role;
        $user->save();

        return response()->json(['message' => 'User role updated successfully']);
    }
}
