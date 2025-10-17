<?php

namespace App\Http\Resources\Admin\Index;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified' => $this->email_verified_at ? true : false,
            'email_verified_at' => $this->email_verified_at
                ? $this->email_verified_at->format('Y-m-d H:i:s')
                : null,
            'last_login_at' => $this->last_login_at
                ? $this->last_login_at->format('Y-m-d H:i:s')
                : null,
            'created_at' => $this->created_at
                ? $this->created_at->format('Y-m-d H:i:s')
                : null,
            'updated_at' => $this->updated_at
                ? $this->updated_at->format('Y-m-d H:i:s')
                : null,

            'class' => $this->when(
                $this->role === 'student',
                function () {
                    return [
                        'id' => $this->classRoom->id ?? null,
                        'name' => $this->classRoom->name ?? null,
                        'type' => $this->classRoom->type ?? null,
                    ];
                }
            ),
        ];
    }
}
