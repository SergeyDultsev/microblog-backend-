<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class AuthController
{
    public function register(RegisterRequest $request)
    {
        $userCount = User::count();

        $roleName = $userCount === 0 ? 'admin' : 'user';

        $role = Role::where('name', $roleName)->first();

        $birthdate = Carbon::createFromDate($request->year, $request->month, $request->day);

        // Проверка что ДР пользователя в прошлом
        if ($birthdate->isFuture()) {
            return response()->json(['error' => 'Birthday may only be in the past'], 401);
        }

        $age = $birthdate->age;

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'surname' => $request->surname,
            'registration_date' => now(),
            'role_id' => $role->id,
            'age' => $age,
            'birthdate' => $birthdate,
            'uuid' => Uuid::uuid4()->toString(),
        ]);

        return response()->json(['message' => 'Successful create account'], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $userRole = $user->hasRole('admin') ? 'admin' : 'user';

        return response()->json([
            'token' => $token,
            'userId' => $user->id,
            'userRole' => $userRole,
            'userAvatar' => $user->avatar,
            'userName' => $user->name,
            'userSurname' => $user->surname,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
