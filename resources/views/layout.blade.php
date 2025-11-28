<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' || app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('artisan-gui::messages.nav.dashboard')) - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [dir="rtl"] {
            direction: rtl;
        }
        .transition-theme {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-theme">
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-theme">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Artisan GUI</h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8 {{ app()->getLocale() === 'ar' || app()->getLocale() === 'fa' ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('artisan-gui.dashboard') }}" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-theme">
                            {{ __('artisan-gui::messages.nav.dashboard') }}
                        </a>
                        <a href="{{ route('artisan-gui.run') }}" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-theme">
                            {{ __('artisan-gui::messages.nav.run_command') }}
                        </a>
                        <a href="{{ route('artisan-gui.catalog') }}" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-theme">
                            {{ __('artisan-gui::messages.nav.catalog') }}
                        </a>
                        <a href="{{ route('artisan-gui.history') }}" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-theme">
                            {{ __('artisan-gui::messages.nav.history') }}
                        </a>
                        <a href="{{ route('artisan-gui.about') }}" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-theme">
                            {{ __('artisan-gui::messages.nav.about') }}
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="themeToggle" type="button" class="p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-theme" aria-label="Toggle dark mode">
                        <svg id="sunIcon" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg id="moonIcon" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <script>
        // Theme Manager
        (function() {
            const themeToggle = document.getElementById('themeToggle');
            const html = document.documentElement;
            
            // Get theme from localStorage or system preference
            function getTheme() {
                const stored = localStorage.getItem('artisan-gui-theme');
                if (stored) {
                    return stored;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            
            // Apply theme
            function setTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
                localStorage.setItem('artisan-gui-theme', theme);
            }
            
            // Initialize theme
            setTheme(getTheme());
            
            // Toggle theme
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            });
            
            // Listen for system theme changes
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (!localStorage.getItem('artisan-gui-theme')) {
                    setTheme(e.matches ? 'dark' : 'light');
                }
            });
        })();

        // SweetAlert2 configuration
        const Swal = window.Swal;
        
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: '{{ __('artisan-gui::messages.alert.success') }}',
                text: message,
                confirmButtonText: '{{ __('artisan-gui::messages.alert.confirm') }}',
                confirmButtonColor: '#10b981'
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: '{{ __('artisan-gui::messages.alert.error') }}',
                text: message,
                confirmButtonText: '{{ __('artisan-gui::messages.alert.confirm') }}',
                confirmButtonColor: '#ef4444'
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
