<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'instance' => $this->instance,
            'phone_number' => $this->phone_number,
            'avatar' =>  $this->avatar ? asset('/storage/' . $this->avatar) : null,
            'email' => $this->email,
            'token' => $this->createToken($request->getClientIp())->plainTextToken,
        ];
    }
}
