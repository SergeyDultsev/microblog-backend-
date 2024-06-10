<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleController
{
    public function updateRole(User $user)
    {
        $userAdmin = Auth::user();

        if (!$userAdmin->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            return response()->json(['error' => 'Admin role not found'], 404);
        }

        if ($user->role_id == $adminRole->id) {
            return response()->json(['message' => 'User is already an admin']);
        }

        // Обновляем роль пользователя
        $user->role_id = $adminRole->id;
        $user->role = 'admin';
        $user->update();

        return response()->json(['message' => 'User role successfully changed']);
    }
}
