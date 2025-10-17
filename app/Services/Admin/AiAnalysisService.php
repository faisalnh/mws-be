<?php

    namespace App\Services\Admin;

    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;

    class AiAnalysisService
    {
        protected string $openAiKey;
        protected string $openAiProjectId;
        protected string $googleAiKey;

        public function __construct()
        {
            $this->openAiKey = env('OPENAI_API_KEY', '');
            $this->openAiProjectId = env('OPENAI_PROJECT_ID', '');
            $this->googleAiKey = env('GOOGLE_AI_API_KEY', '');
        }

        public function analyzeMood(string $mood, ?string $note): string
        {
            $prompt = <<<PROMPT
        Analisis psikologis singkat dan empatik dalam Bahasa Indonesia berdasarkan data berikut:

        Mood: {$mood}
        Catatan: {$note}

        Tuliskan dengan nada positif, empatik, dan memberi semangat.
        PROMPT;

            // 🟢 Coba OpenAI terlebih dahulu
            $result = $this->callOpenAi($prompt);

            // 🔁 Jika gagal, fallback ke Gemini
            if (str_contains($result, 'Gagal') || str_contains($result, 'kuota') || str_contains($result, 'limit')) {
                Log::warning('OpenAI gagal, fallback ke Gemini...');
                $result = $this->callGemini($prompt);
            }

            return $result;
        }

        protected function callOpenAi(string $prompt): string
        {
            try {
                $response = Http::retry(2, 1500)
                    ->withHeaders([
                        'Authorization' => "Bearer {$this->openAiKey}",
                        'Content-Type' => 'application/json',
                        'OpenAI-Project' => $this->openAiProjectId,
                    ])
                    ->post('https://api.openai.com/v1/responses', [
                        'model' => 'gpt-4o-mini',
                        'input' => $prompt,
                        'temperature' => 0.7,
                    ]);

                if ($response->failed()) {
                    $status = $response->status();
                    $body = $response->body();
                    Log::error('OpenAI error', ['status' => $status, 'body' => $body]);

                    return match ($status) {
                        401 => "Gagal: API Key OpenAI tidak valid.",
                        429 => "Gagal: Kuota OpenAI habis.",
                        default => "Gagal menghubungi OpenAI (status {$status}).",
                    };
                }

                $data = $response->json();
                return $data['output'][0]['content'][0]['text'] ?? 'Tidak ada respons dari OpenAI.';
            } catch (\Throwable $e) {
                Log::error('OpenAI exception: ' . $e->getMessage());
                return "Gagal memanggil OpenAI: {$e->getMessage()}";
            }
        }

        protected function callGemini(string $prompt): string
        {
            try {
                $modelName = 'gemini-2.0-flash'; // 🧠 model yang valid dan cepat
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$this->googleAiKey}";

                $response = Http::retry(2, 1500)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                    ]);

                if ($response->failed()) {
                    $status = $response->status();
                    Log::error('Gemini call failed', [
                        'status' => $status,
                        'body' => $response->body(),
                    ]);
                    return match ($status) {
                        401 => "Gagal: API Key Gemini tidak valid.",
                        404 => "Gagal: Model Gemini tidak ditemukan.",
                        429 => "Gagal: Kuota Gemini habis atau terlalu banyak permintaan.",
                        default => "Gagal menghubungi layanan Gemini (status {$status}).",
                    };
                }

                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada respons dari Gemini.';
            } catch (\Throwable $e) {
                Log::error('Gemini exception: ' . $e->getMessage());
                return "Gagal memanggil Gemini: {$e->getMessage()}";
            }
        }
    }
