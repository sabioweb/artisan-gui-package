@extends('artisan-gui::layout')

@section('title', __('artisan-gui::messages.run.title'))

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('artisan-gui::messages.run.title') }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('artisan-gui::messages.run.subtitle') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg transition-theme">
        <div class="px-4 py-5 sm:p-6">
            <form id="commandForm">
                @csrf
                <div class="mb-4">
                    <label for="command" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('artisan-gui::messages.run.form.command') }}</label>
                    <select id="command" name="command" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                        <option value="">{{ __('artisan-gui::messages.run.form.select_command') }}</option>
                        @foreach($commands as $cmdName => $cmd)
                        <option value="{{ $cmdName }}" data-description="{{ $cmd['description'] }}">{{ $cmdName }}</option>
                        @endforeach
                    </select>
                    <p id="commandDescription" class="mt-2 text-sm text-gray-500 dark:text-gray-400"></p>
                </div>

                <div id="parametersContainer" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('artisan-gui::messages.run.form.parameters') }}</label>
                    <div id="parametersFields"></div>
                </div>

                <div class="flex items-center justify-end space-x-reverse space-x-3">
                    <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-theme">
                        {{ __('artisan-gui::messages.run.form.clear') }}
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-theme">
                        {{ __('artisan-gui::messages.run.form.execute') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Output Section -->
    <div id="outputSection" class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg hidden transition-theme">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('artisan-gui::messages.run.output.title') }}</h3>
            <div id="outputContent" class="bg-gray-900 dark:bg-black text-green-400 dark:text-green-300 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                <div id="outputText"></div>
            </div>
            <div id="outputStatus" class="mt-4"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const commandSelect = document.getElementById('command');
    const commandDescription = document.getElementById('commandDescription');
    const parametersContainer = document.getElementById('parametersContainer');
    const parametersFields = document.getElementById('parametersFields');
    const commandForm = document.getElementById('commandForm');
    const outputSection = document.getElementById('outputSection');
    const outputText = document.getElementById('outputText');
    const outputStatus = document.getElementById('outputStatus');

    const commands = @json($commands);
    const translations = {
        noDescription: '{{ __('artisan-gui::messages.run.no_description') }}',
        executing: '{{ __('artisan-gui::messages.run.output.executing') }}',
        success: '{{ __('artisan-gui::messages.run.output.success') }}',
        failed: '{{ __('artisan-gui::messages.run.output.failed') }}',
        serverError: '{{ __('artisan-gui::messages.run.output.server_error', ['error' => ':error']) }}'
    };

    commandSelect.addEventListener('change', function() {
        const selectedCommand = this.value;
        if (selectedCommand && commands[selectedCommand]) {
            const cmd = commands[selectedCommand];
            commandDescription.textContent = cmd.description || translations.noDescription;
            
            // Build parameters fields
            parametersFields.innerHTML = '';
            if (cmd.options && cmd.options.length > 0) {
                parametersContainer.classList.remove('hidden');
                cmd.options.forEach(option => {
                    if (option.name !== 'help' && option.name !== 'quiet' && option.name !== 'verbose') {
                        const div = document.createElement('div');
                        div.className = 'mb-3';
                        div.innerHTML = `
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">${option.name}</label>
                            <input type="text" name="parameters[--${option.name}]" 
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                   placeholder="${option.description || ''}">
                        `;
                        parametersFields.appendChild(div);
                    }
                });
            } else {
                parametersContainer.classList.add('hidden');
            }
        } else {
            commandDescription.textContent = '';
            parametersContainer.classList.add('hidden');
        }
    });

    commandForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const command = formData.get('command');
        const parameters = {};
        
        // Collect parameters
        formData.forEach((value, key) => {
            if (key.startsWith('parameters[')) {
                const paramName = key.match(/parameters\[(.+)\]/)[1];
                if (value) {
                    parameters[paramName] = value;
                }
            }
        });

        outputSection.classList.remove('hidden');
        outputText.textContent = translations.executing;
        outputStatus.innerHTML = '';

        try {
            const response = await fetch('{{ route("artisan-gui.api.execute") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    command: command,
                    parameters: parameters
                })
            });

            const data = await response.json();

            if (data.success) {
                outputText.textContent = data.data.output || translations.success;
                outputStatus.innerHTML = '<div class="text-green-600 dark:text-green-400 font-medium">✓ ' + translations.success + '</div>';
                showSuccess(translations.success);
            } else {
                outputText.textContent = data.data.error || data.data.output || translations.failed;
                outputStatus.innerHTML = '<div class="text-red-600 dark:text-red-400 font-medium">✗ ' + translations.failed + '</div>';
                showError(data.message || translations.failed);
            }
        } catch (error) {
            outputText.textContent = translations.serverError.replace(':error', error.message);
            outputStatus.innerHTML = '<div class="text-red-600 dark:text-red-400 font-medium">✗ {{ __('artisan-gui::messages.alert.error') }}</div>';
            showError(translations.serverError.replace(':error', error.message));
        }
    });

    function resetForm() {
        commandForm.reset();
        commandDescription.textContent = '';
        parametersContainer.classList.add('hidden');
        outputSection.classList.add('hidden');
    }
</script>
@endpush
@endsection
