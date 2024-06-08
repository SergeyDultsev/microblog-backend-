<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController
{
    public function toggleSubscribe(Request $request)
    {
        $userId = Auth::id();
        $targetId = $request->input('target_id');

        // Проверка, что нельзя подписаться на себя
        if ($userId == $targetId) {
            return response()->json(['error' => 'You cannot subscribe to yourself'], 400);
        }

        // Используем транзакцию
        DB::transaction(function () use ($userId, $targetId) {
            // Поиск подписки
            $subscription = Subscription::where('subscriber_id', $userId)
                ->where('target_id', $targetId)
                ->first();

            if (!$subscription) {
                Subscription::create([
                    'subscriber_id' => $userId,
                    'target_id' => $targetId,
                ]);

                User::find($targetId)->increment('subscriber_count');

                User::find($userId)->increment('subscriptions_count');

                return response()->json(['message' => 'You have subscribed'], 201);
            } else {
                $subscription->delete();

                User::find($targetId)->decrement('subscriber_count');

                User::find($userId)->decrement('subscriptions_count');

                return response()->json(['message' => 'Unsubscribed successfully']);
            }
        });

        return response()->json(['error' => 'Failed to toggle subscription'], 500);
    }

    // Получение подписок
    public function getSubscriptions($userId)
    {
        $subscriptions = Subscription::where('subscriber_id', $userId)->pluck('target_id')->toArray();

        return response()->json(['data' => $subscriptions]);
    }

    // Получение подписчиков
    public function getSubscribers($userId)
    {
        $subscribers = Subscription::where('target_id', $userId)->pluck('subscriber_id')->toArray();

        return response()->json(['data' => $subscribers]);
    }
}
