<?php

namespace App\Http\Resources\Admin\Detail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailEmotionalCheckinResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->whenLoaded('user');
        $contact = $this->whenLoaded('contact');

        $baseData = [
            'id' => $this->id,
            'role' => $this->role,
            'mood' => $this->mood,
            'internal_weather' => $this->internal_weather,
            'energy_level' => $this->energy_level,
            'balance' => $this->balance,
            'load' => $this->load,
            'readiness' => $this->readiness,
            'presence_level' => $this->presence_level,
            'capasity_level' => $this->capasity_level,
            'note' => $this->note,
            'contact' => $contact ?? ($this->contact_id === 'no_need' ? ['id' => 'no_need', 'name' => 'No Need'] : null),
            'checked_in_at' => $this->checked_in_at,
            'ai_analysis' => $this->ai_analysis ?? null,
        ];

        // Hanya non-student yang punya atribut tambahan
        if ($this->role !== 'student') {
            $baseData['internal_weather'] = $this->internal_weather ?? null;
            $baseData['energy_level'] = $this->energy_level ?? null;
            $baseData['balance'] = $this->balance ?? null;
            $baseData['load'] = $this->load ?? null;
            $baseData['readiness'] = $this->readiness ?? null;
        }

        // Tambahkan info class jika student
        if ($this->role === 'student' && $user && $user->class) {
            $baseData['class'] = [
                'id' => $user->class->id,
                'name' => $user->class->name,
                'grade_level' => $user->class->grade_level,
                
            ];
        }

        // Tambahkan info user umum
        $baseData['user'] = [
            'id' => $user->id ?? null,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'class_id' => $user->class_id ?? null,
        ];

        return $baseData;
    }
}