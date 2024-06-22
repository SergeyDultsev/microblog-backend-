<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $userRole = $this->hasRole('admin') ? 'admin' : 'user';

        return [
            'id' => $this->id,
            'role' => $userRole,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'about' => $this->about,
            'age' => $this->age,
            'birthdate' => $this->birthdate,
            'subscriptions_count' => $this->subscriptions_count,
            'subscriber_count' => $this->subscriber_count,
            'avatar' => new AvatarResource($this->avatar),
            'registration_date' => $this->registration_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
