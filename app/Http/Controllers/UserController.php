<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
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

    public function updateFullName(UserRequest $request)
    {
        $user = Auth::user();
        $requestData = $request->only(['name', 'surname']);

        if(isset($requestData['name'])) {
            $editDataUser['name'] = $requestData['name'];
        }
        if(isset($requestData['surname'])) {
            $editDataUser['surname'] = $requestData['surname'];
        }

        $user->update($editDataUser);
        $user->save();

        return response()->json(['message' => 'Full name updated successfully.']);
    }

    public function updateAbout(UserRequest $request)
    {
        $user = Auth::user();
        $user->about = $request->about;
        $user->save();

        return response()->json(['message' => 'About section updated successfully.']);
    }

    public function updateAvatar(UserRequest $request)
    {
        $user = Auth::user();

        // Обработка изображений
        if ($request->hasFile('avatar_url')) {
            $file = $request->file('avatar_url');
            $path = $file->store('storage/app/public/images');
            $editDataUser['avatar_url'] = $path;
        }

        $user->update($editDataUser);

        return response()->json(['message' => 'Avatar updated successfully.']);
    }

    public function updateHeaderAvatar(UserRequest $request)
    {
        $user = Auth::user();

        // Обработка изображений
        if ($request->hasFile('head_avatar_url')) {
            $file = $request->file('head_avatar_url');
            $path = $file->store('storage/app/public/images');
            $editDataUser['head_avatar_url'] = $path;
        }

        $user->update($editDataUser);
        return response()->json(['message' => 'Header avatar updated successfully.']);
    }

    public function updateBirthday(UserRequest $request)
    {
        $user = Auth::user();
        
        $currentBirthdate = new Carbon($user->birthdate);
        $day = $request->input('day', $currentBirthdate->day);
        $month = $request->input('month', $currentBirthdate->month);
        $year = $request->input('year', $currentBirthdate->year);

        $birthdate = Carbon::createFromDate($year, $month, $day);

        // Проверка что ДР пользователя в прошлом
        if ($birthdate->isFuture()) {
            return response()->json(['error' => 'Birthday may only be in the past'], 400);
        }

        $age = $birthdate->age;

        $user->update([
            'age' => $age,
            'birthdate' => $birthdate,
        ]);
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
