<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExecuteCommandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware
        // If you need role-based authorization, add middleware in config
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $allowedCommands = config('artisan-gui.allowed_commands', []);

        return [
            'command' => [
                'required',
                'string',
                function ($attribute, $value, $fail) use ($allowedCommands) {
                    if (! empty($allowedCommands) && ! in_array($value, $allowedCommands, true)) {
                        $fail("Command '{$value}' is not allowed.");
                    }
                },
            ],
            'parameters' => 'sometimes|array',
            'parameters.*' => 'nullable',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'command.required' => 'Please select a command.',
            'command.string' => 'Command must be a valid string.',
        ];
    }
}

