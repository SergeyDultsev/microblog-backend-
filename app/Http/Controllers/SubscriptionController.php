<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SubscriptionController
{
    public function toggleSubscribe($targetId)
    {
        $userId = Auth::id();

        // Проверка, что нельзя подписаться на себя
        if ($userId == $targetId) {
            return response()->json(['error' => 'You cannot subscribe to yourself'], 400);
        }

        $responseMessage = '';
        $statusCode = 200;

        // Используем транзакцию
        DB::transaction(function () use ($userId, $targetId, &$responseMessage, &$statusCode) {
            $subscription = Subscription::where('subscriber_id', $userId)
                ->where('target_id', $targetId)
                ->first();

            if (!$subscription) {
                Subscription::create([
                    'subscription_id' => Uuid::uuid4()->toString(),
                    'subscriber_id' => $userId,
                    'target_id' => $targetId,
                ]);

                User::find($targetId)->increment('subscriber_count');

                User::find($userId)->increment('subscriptions_count');

                $responseMessage = 'You have subscribed';
                $statusCode = 201;
            } else {
                $subscription->delete();

                User::find($targetId)->decrement('subscriber_count');

                User::find($userId)->decrement('subscriptions_count');

                $responseMessage = 'Unsubscribed successfully';
                $statusCode = 200;
            }
        });

        return response()->json(['message' => $responseMessage], $statusCode);
    }

    // Получение подписок
    public function getSubscriptions($userId)
    {
        $subscriptions = Subscription::where('subscriber_id', $userId)->pluck('target_id')->toArray();

        return response()->json($subscriptions);
    }

    // Получение подписчиков
    public function getSubscribers($userId)
    {
        $subscribers = Subscription::where('target_id', $userId)->pluck('subscriber_id')->toArray();

        return response()->json($subscribers);
    }
}
