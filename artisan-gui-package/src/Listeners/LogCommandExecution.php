<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Listeners;

use Sabiowebcom\ArtisanGui\Events\CommandExecuted;
use Sabiowebcom\ArtisanGui\Events\CommandFailed;
use Illuminate\Support\Facades\Log;

class LogCommandExecution
{
    /**
     * Handle the event.
     */
    public function handle(CommandExecuted|CommandFailed $event): void
    {
        $commandRun = $event->commandRun;

        Log::info('Artisan GUI command executed', [
            'command' => $commandRun->command,
            'status' => $commandRun->status,
            'executed_by' => $commandRun->executed_by,
            'duration' => $commandRun->duration,
        ]);

        if ($commandRun->isFailed()) {
            Log::error('Artisan GUI command failed', [
                'command' => $commandRun->command,
                'error' => $commandRun->error,
                'exit_code' => $commandRun->exit_code,
            ]);
        }
    }
}

