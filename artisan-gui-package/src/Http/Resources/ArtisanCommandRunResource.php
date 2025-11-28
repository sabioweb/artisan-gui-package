<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArtisanCommandRunResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'command' => $this->command,
            'parameters' => $this->parameters,
            'status' => $this->status,
            'output' => $this->output,
            'error' => $this->error,
            'log_path' => $this->log_path,
            'exit_code' => $this->exit_code,
            'executed_by' => $this->executed_by,
            'executor' => $this->whenLoaded('executor', function () {
                return [
                    'id' => $this->executor->id,
                    'name' => $this->executor->name ?? $this->executor->email,
                ];
            }),
            'started_at' => $this->started_at?->toIso8601String(),
            'finished_at' => $this->finished_at?->toIso8601String(),
            'duration' => $this->duration,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

