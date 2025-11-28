@extends('artisan-gui::layout')

@section('title', __('artisan-gui::messages.about.title'))

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-theme">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ __('artisan-gui::messages.about.title') }}</h2>

                <div class="space-y-6">
                    <!-- Project Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('artisan-gui::messages.about.project.title') }}</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('artisan-gui::messages.about.project.name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $project['name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('artisan-gui::messages.about.project.version') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $version }}</dd>
                            </div>
                        </dl>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">{{ $project['description'] }}</p>
                    </div>

                    <!-- Author Info -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('artisan-gui::messages.about.author.title') }}</h3>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('artisan-gui::messages.about.author.name') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $author['name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('artisan-gui::messages.about.author.email') }}</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <a href="mailto:{{ $author['email'] }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                        {{ $author['email'] }}
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Features -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">{{ __('artisan-gui::messages.about.features.title') }}</h3>
                        <ul class="list-disc list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            @foreach(__('artisan-gui::messages.about.features.list') as $feature)
                            <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- License -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('artisan-gui::messages.about.license') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
