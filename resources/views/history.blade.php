@extends('artisan-gui::layout')

@section('title', __('artisan-gui::messages.history.title'))

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('artisan-gui::messages.history.title') }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('artisan-gui::messages.history.subtitle') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden transition-theme">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.command') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.executor') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.execution_time') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.duration') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('artisan-gui::messages.history.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($runs as $run)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-theme">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $run->command }}</div>
                            @if($run->parameters)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                @foreach($run->parameters as $key => $value)
                                <span class="inline-block mr-2">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($run->status === 'success')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">{{ __('artisan-gui::messages.dashboard.status.success') }}</span>
                            @elseif($run->status === 'failed')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">{{ __('artisan-gui::messages.dashboard.status.failed') }}</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">{{ __('artisan-gui::messages.dashboard.status.running') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $run->executor->name ?? $run->executor->email ?? __('artisan-gui::messages.dashboard.unknown') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div>{{ $run->created_at->format('Y/m/d H:i:s') }}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">{{ $run->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($run->duration)
                                {{ number_format($run->duration, 2) }} {{ __('artisan-gui::messages.history.duration.seconds') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showDetails({{ $run->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-4">{{ __('artisan-gui::messages.history.view') }}</button>
                            @if($run->log_path)
                            <a href="{{ route('artisan-gui.api.runs.log', $run->id) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">{{ __('artisan-gui::messages.history.download_log') }}</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('artisan-gui::messages.history.no_runs') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($runs->hasPages())
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 sm:px-6">
            {{ $runs->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    const translations = {
        detailsTitle: '{{ __('artisan-gui::messages.history.details.title') }}',
        command: '{{ __('artisan-gui::messages.history.details.command') }}',
        status: '{{ __('artisan-gui::messages.history.details.status') }}',
        time: '{{ __('artisan-gui::messages.history.details.time') }}',
        duration: '{{ __('artisan-gui::messages.history.details.duration') }}',
        output: '{{ __('artisan-gui::messages.history.details.output') }}',
        error: '{{ __('artisan-gui::messages.history.details.error') }}',
        noOutput: '{{ __('artisan-gui::messages.history.details.no_output') }}',
        close: '{{ __('artisan-gui::messages.history.details.close') }}',
        success: '{{ __('artisan-gui::messages.dashboard.status.success') }}',
        failed: '{{ __('artisan-gui::messages.dashboard.status.failed') }}',
        seconds: '{{ __('artisan-gui::messages.history.duration.seconds') }}',
        fetchError: '{{ __('artisan-gui::messages.history.details.fetch_error') }}'
    };

    async function showDetails(id) {
        try {
            const response = await fetch(`{{ route('artisan-gui.api.runs.show', '') }}/${id}`);
            const data = await response.json();
            const run = data.data;

            let content = `
                <div class="text-right">
                    <p><strong>${translations.command}:</strong> ${run.command}</p>
                    <p><strong>${translations.status}:</strong> ${run.status === 'success' ? translations.success : translations.failed}</p>
                    <p><strong>${translations.time}:</strong> ${run.created_at}</p>
                    ${run.duration ? `<p><strong>${translations.duration}:</strong> ${run.duration} ${translations.seconds}</p>` : ''}
                    <div class="mt-4">
                        <strong>${translations.output}:</strong>
                        <pre class="bg-gray-900 dark:bg-black text-green-400 dark:text-green-300 p-4 rounded mt-2 text-sm overflow-x-auto">${run.output || translations.noOutput}</pre>
                    </div>
                    ${run.error ? `
                    <div class="mt-4">
                        <strong>${translations.error}:</strong>
                        <pre class="bg-red-50 dark:bg-red-900 text-red-800 dark:text-red-200 p-4 rounded mt-2 text-sm overflow-x-auto">${run.error}</pre>
                    </div>
                    ` : ''}
                </div>
            `;

            Swal.fire({
                title: translations.detailsTitle,
                html: content,
                width: '800px',
                confirmButtonText: translations.close,
                background: window.matchMedia('(prefers-color-scheme: dark)').matches || document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff'
            });
        } catch (error) {
            showError(translations.fetchError);
        }
    }
</script>
@endpush
@endsection
