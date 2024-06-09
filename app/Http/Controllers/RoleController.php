<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\User;

class RoleController
{
    public function updateRole(RoleRequest $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($user->id);

        if ($user->role == 'admin') {
            return response()->json(['message' => 'User admin']);
        }

        $user->role = 'admin';
        $user->save();

        return response()->json(['message' => 'User role successfully changed']);
    }
}
