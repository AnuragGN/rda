<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class AIClient
{
    public function ask(string $prompt): string
    {
        return match (config('ai.driver')) {
            'ollama'     => $this->askOllama($prompt),
            'openrouter' => $this->askOpenRouter($prompt),
			'huggingface' => $this->askHuggingFace($prompt),
			'gemini' => $this->askGemini($prompt),
			'claude' => $this->askClaude($prompt),
            default      => throw new \Exception('Invalid AI driver'),
        };
    }

    private function askOllama(string $prompt): string
    {
        $response = Http::timeout(60)->post(
            config('ai.ollama.url') . '/api/generate',
            [
                'model'  => config('ai.ollama.model'),
                'prompt' => $prompt,
                'stream' => false,
            ]
        );

        return $response->json('response') ?? '';
    }

    private function askOpenRouter(string $prompt): string
    {
        $response = Http::timeout(60)
			->withOptions([
				'verify' => false, // ⚠️ TEMP FIX
			])
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('ai.openrouter.key'),
                'Content-Type'  => 'application/json',
            ])
            ->post(config('ai.openrouter.url'), [
                'model' => config('ai.openrouter.model'),
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

        return $response->json('choices.0.message.content') ?? '';
    }
	
	
	private function askHuggingFace($prompt, $retry = 0)
	{
		$response = Http::timeout(90)
			->withOptions([
				'verify' => false, // LOCAL ONLY
			])
			->withHeaders([
				'Authorization' => 'Bearer ' . config('ai.huggingface.key'),
				'Content-Type'  => 'application/json',
			])
			->post(config('ai.huggingface.url'), [
				'inputs' => $prompt,
				'parameters' => [
					'max_new_tokens'   => 300,
					'temperature'      => 0.7,
					'return_full_text' => false,
				],
			]);
	
		$data = $response->json();
		
		// Model loading (retry)
		if (isset($data['error']) && str_contains(strtolower($data['error']), 'loading')) {
			if ($retry < 2) {
				sleep(5);
				return $this->askHuggingFace($prompt, $retry + 1);
			}
			return '⏳ AI model is warming up. Please try again shortly.';
		}

		// Standard response
		if (is_array($data) && isset($data[0]['generated_text'])) {
			return trim($data[0]['generated_text']);
		}

		// Object response fallback
		if (isset($data['generated_text'])) {
			return trim($data['generated_text']);
		}

		logger()->warning('Unexpected Hugging Face response', [
			'response' => $data
		]);

		return '';
	}

	private function askGemini(string $prompt): string
	{
		// 1. Correct URL structure: The model name MUST be in the URL path, not the body.
		$model = config('ai.gemini.model');
		$baseUrl = rtrim(config('ai.gemini.url'), '/');
		$url = "{$baseUrl}/{$model}:generateContent";

		$response = Http::timeout(60)
			->withOptions(['verify' => false]) // Still use this if local SSL is an issue
			->withHeaders([
				// 2. AUTH: Google AI Studio uses 'x-goog-api-key', NOT 'Authorization: Bearer'
				'x-goog-api-key' => config('ai.gemini.key'),
				'Content-Type'   => 'application/json',
			])
			->post($url, [
				// 3. BODY: Google uses 'contents' and 'parts', NOT 'messages' and 'role/content'
				'contents' => [
					[
						'parts' => [
							['text' => $prompt]
						]
					]
				]
			]);

		// 4. RESPONSE: Structure is 'candidates.0.content.parts.0.text'
		if ($response->successful()) {
			return $response->json('candidates.0.content.parts.0.text') ?? '';
		}

		// Debugging: This will show you the exact error if it fails again
		throw new \Exception("Gemini API Error: " . $response->body());
	}
	
	private function askClaude(string $prompt): string
	{
		$response = Http::timeout(60)
			->withOptions(['verify' => false])
			->withHeaders([
				'x-api-key' => config('ai.claude.key'),
				'anthropic-version' => '2023-06-01',
				'content-type' => 'application/json',
			])
			->post(config('ai.claude.url'), [
				'model' => config('ai.claude.model'), // e.g. claude-3-haiku-20240307
				'max_tokens' => 500,
				'messages' => [
					[
						'role' => 'user',
						'content' => $prompt
					]
				]
			]);

		if ($response->successful()) {
			return $response->json('content.0.text') ?? '';
		}

		throw new \Exception("Claude API Error: " . $response->body());
	}
}
