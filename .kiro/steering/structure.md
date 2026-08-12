# Project Structure

## Top-Level Layout

```
app/                    # Application logic
bootstrap/              # Laravel bootstrap
config/                 # Config files (including per-client custom/)
public/                 # Web root (js/, css/, assets/)
resources/views/        # Blade templates
routes/                 # web.php, api.php, etc.
app/Services/AI/        # AI feature service layer
```

## app/ Directory

```
app/
├── CCT/ GNA/ FFP/ HGA/ JCF/ JSV/ NIF/ NTC/ GMF/ Mercy/
│   └── *Statement.php          # Per-client statement generators
├── Console/Commands/           # Artisan commands
├── Events/                     # Pusher/broadcast events
├── Forms/                      # Form definition classes
├── Helpers/                    # Utility classes (GConst, GnUtils, Data, etc.)
├── Http/
│   ├── Controllers/
│   │   ├── Agency/             # Agency/advisor portal controllers
│   │   ├── Auth/               # Login, 2FA, password reset
│   │   ├── Donor/              # Donor portal controllers
│   │   ├── Seeker/             # Grant seeker controllers
│   │   ├── SupportStaff/       # Staff portal controllers
│   │   └── AIAssistantController.php
│   ├── Middleware/
│   └── Traits/                 # Shared controller traits (AuthTrait, etc.)
├── Mail/                       # Mailable classes
├── Models/                     # Eloquent models (extend BaseModel)
├── Providers/                  # Service providers
├── Rules/                      # Custom validation rules
└── Services/
    ├── AI/
    │   ├── AIClient.php        # Low-level driver dispatcher
    │   ├── AITextService.php   # High-level AI actions
    │   └── Prompts/            # One prompt builder class per action
    ├── AiService.php           # Legacy AI service (direct HTTP, not driver-based)
    ├── DafFlowService.php
    └── MailService.php
```

## config/ Directory

```
config/
├── ai.php                      # AI driver + model configuration
├── client.php                  # Active client settings
├── charities.php               # Charity data
└── custom/
    └── *_config.php            # Per-client overrides (ffp, gna, cct, etc.)
```

## resources/views/ Directory

Views are organized by user role / client area:

```
resources/views/
├── agency/                     # Agency and advisor views
├── auth/                       # Login, 2FA, password reset
├── donor/                      # Donor portal views
├── seeker/                     # Grant seeker views
├── support_staff/              # Staff views
├── emails/                     # Email templates
├── layouts/                    # Base layout templates
├── common/                     # Shared partials
└── {client}/                   # Client-specific overrides (ffp, gna, cct, etc.)
```

## Key Conventions

- **Multi-tenancy**: Client identity is resolved via `ClientInfo` and `APP_CLIENT` env. Views are resolved with `ClientInfo::clientViewFor('path')` to allow per-client overrides.
- **Models**: All Eloquent models extend `App\Models\BaseModel`. Static query helpers (e.g. `getById`, `getByUser`) are defined on the model.
- **Constants**: Global constants live in `App\Helpers\GConst`. Session keys, roles, and feature flags are defined there.
- **AI actions**: Adding a new AI action requires: a Prompt class in `Prompts/`, a method in `AITextService`, a case in `AIAssistantController::process()`, and a `data-type` button in the Blade view.
- **No frontend build step**: JS/CSS are served directly from `public/`. No webpack, Vite, or npm.
