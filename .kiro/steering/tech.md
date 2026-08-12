# Tech Stack

## Backend

- **Framework**: Laravel 12 (PHP ^8.2)
- **Auth**: Custom session-based auth with optional 2FA; SAML2 SSO via `onelogin/php-saml`
- **ORM**: Eloquent (extends custom `BaseModel`)
- **HTTP Client**: Guzzle via Laravel `Http` facade
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **Payments**: Authorize.Net (custom integration), Stripe (`stripe/stripe-php`)
- **SMS**: Twilio (`twilio/sdk`)
- **Real-time**: Pusher (`pusher/pusher-php-server`)
- **Queue/Cache**: Laravel Cache (used for AI response caching)

## Frontend

- **Templating**: Blade (`.blade.php`)
- **JS**: jQuery + vanilla JS (no build pipeline / no npm)
- **Rich Text**: Summernote editor (`.summernote('code', ...)` for setting content)
- **CSS Framework**: Bootstrap (with custom `btn-accent` class)
- **Icons**: Font Awesome

## AI Integration

- **Config file**: `config/ai.php`
- **Driver env var**: `AI_DRIVER` — supported values: `openrouter`, `ollama`, `huggingface`, `gemini`, `claude`
- **Service layer**: `App\Services\AI\AIClient` → `App\Services\AI\AITextService`
- **Prompt classes**: `app/Services/AI/Prompts/` (one class per action)
- **Controller**: `App\Http\Controllers\AIAssistantController`
- **Frontend**: `public/js/ai-helper.js` (jQuery, posts to `/ai/process`)
- **Caching**: Laravel Cache keyed by `md5(user + type + prompt + limits)`, TTL via `AI_CACHE_TTL` env

## Common Commands

```bash
# Install dependencies
composer install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Run scheduled commands
php artisan schedule:run

# Run queue worker
php artisan queue:work

# Tinker (REPL)
php artisan tinker
```

## Environment Variables (Key)

| Variable | Purpose |
|---|---|
| `AI_DRIVER` | Active AI backend (`openrouter`, `ollama`, `gemini`, `claude`, `huggingface`) |
| `AI_API_KEY` | API key for active driver |
| `AI_MODEL` | Model name for active driver |
| `AI_CACHE_TTL` | Cache TTL in seconds (default: 3600) |
| `OPENROUTER_API_KEY` | OpenRouter key (legacy `AiService`) |
| `GEMINI_API_KEY` / `GEMINI_MODEL` | Gemini config |
| `CLAUDE_API_KEY` / `CLAUDE_MODEL` | Claude config |
| `OLLAMA_URL` / `OLLAMA_MODEL` | Local Ollama config |
| `APP_CLIENT` | Active client identifier (e.g. `ffp`, `gna`, `cct`) |
