<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active AI Driver
    |--------------------------------------------------------------------------
    | Supported drivers: openrouter, ollama, huggingface
    */
    'driver' => env('AI_DRIVER', 'openrouter'),
	'ai_cache_ttl' => env('AI_CACHE_TTL', 3600),
    /*
    |--------------------------------------------------------------------------
    | OpenRouter Configuration (Production)
    |--------------------------------------------------------------------------
    */
    'openrouter' => [
        'url'   => env('AI_API_URL', 'https://openrouter.ai/api/v1/chat/completions'),
        'key'   => env('AI_API_KEY'),
        'model' => env('AI_MODEL'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Gemini Configuration
    |--------------------------------------------------------------------------
    */
    'gemini' => [
        'url'   => env('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/'),
        'key'   => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-pro'),
    ],
	
	/*
    |--------------------------------------------------------------------------
    | claude Configuration
    |--------------------------------------------------------------------------
    */
    'claude' => [
        'url'   => env('CLAUDE_API_URL', 'https://api.anthropic.com/v1/messages'),
        'key'   => env('CLAUDE_API_KEY'),
        'model' => env('CLAUDE_MODEL', 'claude-3-haiku-20240307'),
    ],


    /*
    |--------------------------------------------------------------------------
    | Ollama Configuration (Local / Dev)
    |--------------------------------------------------------------------------
    */
    'ollama' => [
        'url'   => env('OLLAMA_URL', 'http://localhost:11434/api/generate'),
        'model' => env('OLLAMA_MODEL', 'mistral'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Hugging Face Configuration (Testing)
    |--------------------------------------------------------------------------
    */
	'huggingface' => [
		'url'   => env('AI_API_URL'),
		'key'   => env('AI_API_KEY'),
		'model' => env('AI_MODEL'),
	],
];

