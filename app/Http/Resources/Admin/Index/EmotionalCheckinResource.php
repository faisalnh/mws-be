<?php

namespace App\Http\Resources\Admin\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmotionalCheckinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'role' => $this->role,
            'mood' => $this->mood,
            'internal_weather' => $this->internal_weather,
            'energy_level' => $this->energy_level,
            'balance' => $this->balance,
            'load' => $this->load,
            'readiness' => $this->readiness,
            'contact_id' => $this->contact_id === 'no_need' ? 'no_need' : $this->contact_id,
            'presence_level' => $this->presence_level,
            'capasity_level' => $this->capasity_level,
            'note' => $this->note,
            'checked_in_at' => $this->checked_in_at,
            'created_at' => $this->created_at,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),
        ];
    }
}
