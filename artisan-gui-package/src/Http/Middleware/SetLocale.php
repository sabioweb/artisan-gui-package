<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for locale in query parameter
        if ($request->has('lang')) {
            $locale = $request->query('lang');
            if (in_array($locale, $this->getAvailableLocales(), true)) {
                app()->setLocale($locale);
            }
        }
        // Check for locale in session
        elseif (session()->has('artisan_gui_locale')) {
            $locale = session('artisan_gui_locale');
            if (in_array($locale, $this->getAvailableLocales(), true)) {
                app()->setLocale($locale);
            }
        }
        // Use config default if set
        elseif ($configLocale = config('artisan-gui.locale')) {
            app()->setLocale($configLocale);
        }

        return $next($request);
    }

    /**
     * Get available locales from language files.
     *
     * @return array<string>
     */
    protected function getAvailableLocales(): array
    {
        $langPath = __DIR__.'/../../../resources/lang';
        $locales = [];

        if (is_dir($langPath)) {
            $directories = scandir($langPath);
            foreach ($directories as $dir) {
                if ($dir !== '.' && $dir !== '..' && is_dir($langPath.'/'.$dir)) {
                    $locales[] = $dir;
                }
            }
        }

        return $locales ?: ['en'];
    }
}

