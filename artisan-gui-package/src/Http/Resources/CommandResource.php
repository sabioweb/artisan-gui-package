<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource['name'],
            'description' => $this->resource['description'],
            'arguments' => $this->resource['arguments'] ?? [],
            'options' => $this->resource['options'] ?? [],
        ];
    }
}

