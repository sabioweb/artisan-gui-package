<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ArtisanGuiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/artisan-gui.php',
            'artisan-gui'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/artisan-gui.php' => config_path('artisan-gui.php'),
        ], 'artisan-gui-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'artisan-gui-migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/artisan-gui'),
        ], 'artisan-gui-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/artisan-gui'),
        ], 'artisan-gui-lang');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'artisan-gui');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'artisan-gui');

        $this->registerEvents();
        $this->registerCommands();
        $this->registerRoutes();
    }

    /**
     * Register package events and listeners.
     */
    protected function registerEvents(): void
    {
        $events = $this->app['events'];

        $events->listen(
            \Sabiowebcom\ArtisanGui\Events\CommandExecuted::class,
            \Sabiowebcom\ArtisanGui\Listeners\LogCommandExecution::class
        );

        $events->listen(
            \Sabiowebcom\ArtisanGui\Events\CommandFailed::class,
            \Sabiowebcom\ArtisanGui\Listeners\LogCommandExecution::class
        );
    }

    /**
     * Register package commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Sabiowebcom\ArtisanGui\Console\Commands\TranslateCommand::class,
            ]);
        }
    }

    /**
     * Register package routes.
     *
     * Routes are grouped under dedicated URL and name prefixes to avoid any
     * clash with the host Laravel application. Defaults:
     * - URL prefix: 'artisan-gui' (configurable)
     * - Route name prefix: 'artisan-gui.' (auto-applied to every route)
     */
    protected function registerRoutes(): void
    {
        $prefix = config('artisan-gui.route_prefix', 'artisan-gui');
        $middleware = config('artisan-gui.middleware', ['web', 'auth']);

        // Add locale middleware if configured
        $locale = config('artisan-gui.locale');
        if ($locale) {
            $middleware[] = function ($request, $next) use ($locale) {
                app()->setLocale($locale);
                return $next($request);
            };
        }

        Route::prefix($prefix)
            ->middleware($middleware)
            ->name('artisan-gui.') // Prefix automatically prepended to every route name
            ->group(function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
    }
}

