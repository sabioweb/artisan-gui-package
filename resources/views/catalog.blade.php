@extends('artisan-gui::layout')

@section('title', __('artisan-gui::messages.catalog.title'))

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('artisan-gui::messages.catalog.title') }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('artisan-gui::messages.catalog.subtitle') }}</p>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <input type="text" id="searchInput" placeholder="{{ __('artisan-gui::messages.catalog.search.placeholder') }}" 
               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
    </div>

    <!-- Commands Grid -->
    <div id="commandsGrid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($commands as $cmdName => $cmd)
        <div class="command-card bg-white dark:bg-gray-800 shadow rounded-lg p-4 hover:shadow-md transition-shadow" 
             data-name="{{ $cmdName }}" 
             data-description="{{ $cmd['description'] ?? '' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $cmdName }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $cmd['description'] ?? __('artisan-gui::messages.catalog.no_description') }}</p>
                    
                    @if(isset($cmd['options']) && count($cmd['options']) > 0)
                    <div class="mt-2">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">{{ __('artisan-gui::messages.catalog.options') }}:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($cmd['options'], 0, 5) as $option)
                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">{{ $option['name'] }}</span>
                            @endforeach
                            @if(count($cmd['options']) > 5)
                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">+{{ count($cmd['options']) - 5 }} {{ __('artisan-gui::messages.catalog.more') }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('artisan-gui.run') }}?command={{ $cmdName }}" 
                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-theme">
                    {{ __('artisan-gui::messages.catalog.execute') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div id="noResults" class="hidden text-center py-8 text-gray-500 dark:text-gray-400">
        {{ __('artisan-gui::messages.catalog.no_results') }}
    </div>
</div>

@push('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const commandsGrid = document.getElementById('commandsGrid');
    const noResults = document.getElementById('noResults');
    const commandCards = document.querySelectorAll('.command-card');

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        commandCards.forEach(card => {
            const name = card.dataset.name.toLowerCase();
            const description = card.dataset.description.toLowerCase();
            
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visibleCount === 0 && searchTerm) {
            noResults.classList.remove('hidden');
            commandsGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            commandsGrid.classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection
