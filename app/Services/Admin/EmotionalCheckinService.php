<?php

namespace App\Services\Admin;

use App\Models\EmotionalCheckin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Admin\AiAnalysisService;

class EmotionalCheckinService
{
    protected AiAnalysisService $aiService;

    public function __construct(AiAnalysisService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Mapping Need Supports → contact_id
     */
    protected function mapContactId(array $data): ?int
    {
        // Jika user menandai tidak butuh support
        if (!empty($data['no_need']) && $data['no_need'] === true) {
            return null;
        }

        // Jika user membutuhkan bantuan (need_support = true)
        if (!empty($data['need_support']) && $data['need_support'] === true) {
            // Ambil contact_id dari request jika ada
            if (!empty($data['contact_id'])) {
                return (int) $data['contact_id'];
            }

            // Jika tidak dikirim, coba fallback ambil user dengan role tertentu (misal Director)
            $contact = \App\Models\User::where('name', 'Director')->first();
            return $contact ? $contact->id : null;
        }

        // Jika tidak ada indikasi apapun (tidak butuh / tidak kirim)
        return null;
    }


    public function searchEmotionalCheckin(array $relations = [], int $paginate = 10, ?string $search = null)
    {
        $query = EmotionalCheckin::with($relations)->orderByDesc('checked_in_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('mood', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        return $query->paginate($paginate);
    }

    public function createEmotionalCheckin(array $data)
    {
        DB::beginTransaction();
        try {
            // 🟡 Deteksi jika contact_id dikirim kosong
            if (!isset($data['contact_id']) || $data['contact_id'] === '' || $data['contact_id'] === null) {
                $data['contact_id'] = 'no_need';
            }

            // 🧠 Tangani multiple mood
            $moodInput = $data['mood'] ?? null;

            if (is_string($moodInput)) {
                // Jika dikirim "happy neutral" atau "happy, neutral"
                $moodInput = preg_split('/[\s,]+/', trim($moodInput));
            }

            if (empty($moodInput)) {
                $moodInput = null;
            }

            $checkin = EmotionalCheckin::create([
                'user_id' => $data['user_id'],
                'role' => $data['role'],
                'mood' => $moodInput, // sekarang bisa array
                'internal_weather' => $data['internal_weather'] ?? null,
                'presence_level' => $data['presence_level'],
                'capasity_level' => $data['capasity_level'],
                'note' => $data['note'] ?? null,
                'checked_in_at' => $data['checked_in_at'],
                'energy_level' => $data['energy_level'] ?? null,
                'balance' => $data['balance'] ?? null,
                'load' => $data['load'] ?? null,
                'readiness' => $data['readiness'] ?? null,
                'contact_id' => $data['contact_id'] ?? $this->mapContactId($data),
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('❌ Gagal menyimpan Emotional Check-in: ' . $th->getMessage());
            throw $th;
        }

        // 🧠 AI Analysis
        try {
            // ubah array jadi teks agar bisa dibaca oleh AI
            $moodText = is_array($checkin->mood) ? implode(', ', $checkin->mood) : $checkin->mood;
            $analysis = $this->aiService->analyzeMood($moodText, $checkin->note);

            if ($analysis && !str_contains($analysis, 'Gagal')) {
                $checkin->ai_analysis = $analysis;
                $checkin->save();
            }
        } catch (\Throwable $e) {
            Log::error('❌ AI Analysis Error: ' . $e->getMessage());
        }

        return $checkin->fresh(['user.class', 'contact']);
    }


    public function findByUuidWithRelation(string $id, array $relations = [])
    {
        $checkin = EmotionalCheckin::with($relations)->find($id);

        if (!$checkin) {
            throw new ModelNotFoundException("Emotional Check-in not found.");
        }

        return $checkin->fresh(['user.class', 'contact']);
    }

    public function updateEmotionalCheckin(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $checkin = EmotionalCheckin::findOrFail($id);

            // 🟡 Jika contact_id kosong string
            if (array_key_exists('contact_id', $data) && $data['contact_id'] === '') {
                $data['contact_id'] = 'no_need';
            }

            $checkin->update([
                'role' => $data['role'] ?? $checkin->role,
                'mood' => $data['mood'] ?? $checkin->mood,
                'internal_weather' => $data['internal_weather'] ?? $checkin->internal_weather,
                'presence_level' => $data['presence_level'] ?? $checkin->presence_level,
                'capasity_level' => $data['capasity_level'] ?? $checkin->capasity_level,
                'note' => $data['note'] ?? $checkin->note,
                'checked_in_at' => $data['checked_in_at'] ?? $checkin->checked_in_at,
                'energy_level' => $data['energy_level'] ?? $checkin->energy_level,
                'balance' => $data['balance'] ?? $checkin->balance,
                'load' => $data['load'] ?? $checkin->load,
                'readiness' => $data['readiness'] ?? $checkin->readiness,
                'contact_id' => $data['contact_id'] ?? $checkin->contact_id,
            ]);

            DB::commit();
            return $checkin->fresh(['user.class', 'contact']);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to update emotional checkin: ' . $th->getMessage());
            throw $th;
        }
    }

    public function destroyByUuid(string $id)
    {
        DB::beginTransaction();
        try {
            $checkin = EmotionalCheckin::findOrFail($id);
            $checkin->delete();

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Failed to delete emotional checkin: ' . $th->getMessage());
            throw $th;
        }
    }

    public function success($data, int $code = 200, string $message = 'Success')
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function successPaginate($data, int $code = 200)
    {
        return response()->json($data, $code);
    }
}
