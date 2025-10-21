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
You are an empathetic and positive psychological assistant.
Please provide a short, warm, and encouraging psychological analysis **in English** based on the following data:

Mood: {$mood}
Note: {$note}

Write naturally as if you are talking to the person directly, focusing on emotional support and motivation.
PROMPT;

        // 🟢 Try OpenAI first
        $result = $this->callOpenAi($prompt);

        // 🔁 If OpenAI fails, fallback to Gemini
        if ($this->isFailure($result)) {
            Log::warning('OpenAI failed, falling back to Gemini...');
            $result = $this->callGemini($prompt);
        }

        return $result;
    }

    /**
     * Detect if result contains a failure message
     */
    protected function isFailure(string $result): bool
    {
        $keywords = ['Gagal', 'Failed', 'Error', 'limit', 'quota'];
        foreach ($keywords as $word) {
            if (stripos($result, $word) !== false) {
                return true;
            }
        }
        return false;
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
                    401 => "Failed: Invalid OpenAI API key.",
                    429 => "Failed: OpenAI quota exhausted.",
                    default => "Failed to contact OpenAI (status {$status}).",
                };
            }

            $data = $response->json();
            return $data['output'][0]['content'][0]['text'] ?? 'No response from OpenAI.';
        } catch (\Throwable $e) {
            Log::error('OpenAI exception: ' . $e->getMessage());
            return "Failed to call OpenAI: {$e->getMessage()}";
        }
    }

    protected function callGemini(string $prompt): string
    {
        try {
            $modelName = 'gemini-2.0-flash';
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
                    401 => "Failed: Invalid Gemini API key.",
                    404 => "Failed: Gemini model not found.",
                    429 => "Failed: Gemini quota exhausted or too many requests.",
                    default => "Failed to contact Gemini (status {$status}).",
                };
            }

            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from Gemini.';
        } catch (\Throwable $e) {
            Log::error('Gemini exception: ' . $e->getMessage());
            return "Failed to call Gemini: {$e->getMessage()}";
        }
    }
    }
