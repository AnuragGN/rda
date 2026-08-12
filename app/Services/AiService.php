<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    public function getAiResponse($prompt)
    {
        try {
            // 1. Try Google Gemini via OpenRouter
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => env('GEMINI_MODEL'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? 'No response.';
            }

            // 2. Fallback to Hugging Face (Free, Self-Hosted)
            $huggingface = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('HUGGINGFACE_API_KEY'),
            ])->post("https://api-inference.huggingface.co/models/" . env('HUGGINGFACE_MODEL'), [
                'inputs' => $prompt,
            ]);

            if ($huggingface->successful()) {
                return $huggingface->json()[0]['generated_text'] ?? 'No response.';
            }

            // 3. Optional OpenAI GPT (Paid)
            $openai = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => env('OPENAI_MODEL'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if ($openai->successful()) {
                return $openai->json()['choices'][0]['message']['content'] ?? 'No response.';
            }

            return "Unable to process your request right now.";

        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
