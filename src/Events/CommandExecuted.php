<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Events;

use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommandExecuted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public ArtisanCommandRun $commandRun
    ) {
    }
}

