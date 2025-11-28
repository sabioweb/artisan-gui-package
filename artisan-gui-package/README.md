# Artisan GUI Package

Laravel package for executing Artisan commands through a web GUI.

## Installation

### Via Composer

```bash
composer require sabiowebcom/artisan-gui
```

### Publishing Package Files

```bash
# Publish config
php artisan vendor:publish --tag=artisan-gui-config

# Publish migrations
php artisan vendor:publish --tag=artisan-gui-migrations

# Publish views (optional - for customization)
php artisan vendor:publish --tag=artisan-gui-views

# Publish language files (optional - for customization)
php artisan vendor:publish --tag=artisan-gui-lang
```

### Running Migrations

```bash
php artisan migrate
```

## Configuration

After publishing the config, edit the `config/artisan-gui.php` file:

```php
return [
    // Route prefix for all package endpoints
    'route_prefix' => 'artisan-gui',
    
    // Middleware stack applied to package routes
    'middleware' => ['web', 'auth'],
    
    // Whitelisted Artisan commands
    'allowed_commands' => [
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'optimize:clear',
        'queue:work',
        'queue:restart',
        'migrate:status',
        'db:seed',
    ],
    
    // Allowed roles (if you enforce role-based access)
    'allowed_roles' => ['admin', 'devops'],
    
    // Path used to store execution logs
    'log_storage_path' => storage_path('logs/artisan-gui'),
    
    // Maximum execution time in seconds
    'max_execution_time' => 300,
];
```

## Usage

After installation and configuration, you can access the following routes through your browser:

- **Dashboard**: `/artisan-gui`
- **Run Command**: `/artisan-gui/run`
- **Commands Catalog**: `/artisan-gui/catalog`
- **History**: `/artisan-gui/history`
- **About**: `/artisan-gui/about`

## API Endpoints

### Execute Command

```http
POST /artisan-gui/api/execute
Content-Type: application/json

{
    "command": "cache:clear",
    "parameters": {}
}
```

### Get Commands List

```http
GET /artisan-gui/api/commands
```

### Get Execution Details

```http
GET /artisan-gui/api/runs/{id}
```

### Download Log

```http
GET /artisan-gui/api/runs/{id}/log
```

## Security

- All commands must be in the `allowed_commands` list
- Route access is controlled through middleware
- All executions are logged in the `artisan_command_runs` table
- Execution logs are stored in the specified path

## Customization

### Overriding Views

```bash
php artisan vendor:publish --tag=artisan-gui-views
```

Then edit the Blade files in `resources/views/vendor/artisan-gui/`.

### Changing Route Prefix

In the `.env` file:

```env
ARTISAN_GUI_PREFIX=admin/artisan
```

Or in `config/artisan-gui.php`:

```php
'route_prefix' => 'admin/artisan',
```

## Internationalization (i18n)

The package supports multiple languages with English as the base language. All UI texts are translatable.

### Available Languages

By default, the package includes English translations. Additional languages can be generated using the auto-translation feature.

### Setting Locale

You can set the locale in several ways:

1. **Via Config** (applies to all routes):
```php
// config/artisan-gui.php
'locale' => 'fa', // or 'ar', 'es', 'fr', etc.
```

2. **Via Environment Variable**:
```env
ARTISAN_GUI_LOCALE=fa
```

3. **Via Query Parameter** (per request):
```
/artisan-gui?lang=fa
```

4. **Via Session**:
```php
session(['artisan_gui_locale' => 'fa']);
```

### Auto-Translation

The package includes an auto-translation feature that can generate translations for multiple languages using translation APIs.

#### Setup

1. Enable auto-translation in config:
```php
// config/artisan-gui.php
'auto_translation' => [
    'enabled' => true,
    'provider' => 'google', // or 'deepl'
    'api_key' => env('ARTISAN_GUI_TRANSLATION_API_KEY'),
    'target_languages' => 'fa,ar,es,fr,de',
],
```

2. Set your API key in `.env`:
```env
ARTISAN_GUI_AUTO_TRANSLATE=true
ARTISAN_GUI_TRANSLATION_PROVIDER=google
ARTISAN_GUI_TRANSLATION_API_KEY=your-api-key-here
ARTISAN_GUI_TARGET_LANGUAGES=fa,ar,es,fr,de
```

#### Generate Translations

Run the translation command:
```bash
php artisan artisan-gui:translate
```

This will generate translation files for all target languages in `resources/lang/{locale}/messages.php`.

**Note**: The current implementation includes placeholder methods for translation APIs. You'll need to implement the actual API integration based on your chosen provider (Google Cloud Translation, DeepL, etc.).

#### Manual Translation

You can also manually create translation files:

1. Copy the English language file:
```bash
cp resources/lang/en/messages.php resources/lang/fa/messages.php
```

2. Translate the values in the file while keeping the keys unchanged.

### Adding New Translation Keys

1. Add the key-value pair to `resources/lang/en/messages.php`
2. Run the translation command or manually translate to other languages
3. Use in views: `{{ __('artisan-gui::messages.your.key') }}`

## Testing

```bash
composer test
```

## License

MIT License

## Support

To report issues or suggest new features, please create an issue.
