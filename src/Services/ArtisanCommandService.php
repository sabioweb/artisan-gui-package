<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Services;

use Sabiowebcom\ArtisanGui\Events\CommandExecuted;
use Sabiowebcom\ArtisanGui\Events\CommandFailed;
use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\BufferedOutput;

class ArtisanCommandService
{
    /**
     * Execute an Artisan command.
     *
     * @param  string  $command
     * @param  array<string, mixed>  $parameters
     * @param  Authenticatable|null  $user
     * @return ArtisanCommandRun
     */
    public function execute(string $command, array $parameters = [], ?Authenticatable $user = null): ArtisanCommandRun
    {
        $this->validateCommand($command);

        $output = new BufferedOutput();
        $logPath = $this->generateLogPath($command);

        $startTime = now();
        $status = 'running';

        try {
            $exitCode = Artisan::call($command, $parameters, $output);
            $outputContent = $output->fetch();
            $endTime = now();

            $status = $exitCode === 0 ? 'success' : 'failed';

            // Save output to log file
            File::ensureDirectoryExists(dirname($logPath));
            File::put($logPath, $outputContent);

            $commandRun = ArtisanCommandRun::create([
                'command' => $command,
                'parameters' => $parameters,
                'status' => $status,
                'output' => $outputContent,
                'log_path' => $logPath,
                'exit_code' => $exitCode,
                'executed_by' => $user?->id,
                'started_at' => $startTime,
                'finished_at' => $endTime,
            ]);

            if ($status === 'success') {
                event(new CommandExecuted($commandRun));
            } else {
                event(new CommandFailed($commandRun));
            }

            return $commandRun;
        } catch (\Exception $e) {
            $endTime = now();
            $errorMessage = $e->getMessage();
            $outputContent = $output->fetch();

            File::ensureDirectoryExists(dirname($logPath));
            File::put($logPath, $outputContent."\n\nError: ".$errorMessage);

            $commandRun = ArtisanCommandRun::create([
                'command' => $command,
                'parameters' => $parameters,
                'status' => 'failed',
                'output' => $outputContent,
                'error' => $errorMessage,
                'log_path' => $logPath,
                'exit_code' => 1,
                'executed_by' => $user?->id,
                'started_at' => $startTime,
                'finished_at' => $endTime,
            ]);

            event(new CommandFailed($commandRun));

            Log::error('Artisan GUI command execution failed', [
                'command' => $command,
                'parameters' => $parameters,
                'error' => $errorMessage,
                'user' => $user?->id,
            ]);

            return $commandRun;
        }
    }

    /**
     * Get all available Artisan commands.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getAvailableCommands(): array
    {
        $commands = Artisan::all();
        $allowedCommands = config('artisan-gui.allowed_commands', []);
        $result = [];

        foreach ($commands as $name => $command) {
            if (empty($allowedCommands) || in_array($name, $allowedCommands, true)) {
                $result[$name] = [
                    'name' => $name,
                    'description' => $command->getDescription(),
                    'arguments' => $this->getCommandArguments($command),
                    'options' => $this->getCommandOptions($command),
                ];
            }
        }

        ksort($result);

        return $result;
    }

    /**
     * Validate if command is allowed.
     *
     * @param  string  $command
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function validateCommand(string $command): void
    {
        $allowedCommands = config('artisan-gui.allowed_commands', []);

        if (! empty($allowedCommands) && ! in_array($command, $allowedCommands, true)) {
            throw new \InvalidArgumentException("Command '{$command}' is not allowed.");
        }
    }

    /**
     * Get command arguments.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return array<string, mixed>
     */
    protected function getCommandArguments($command): array
    {
        $arguments = [];
        $definition = $command->getDefinition();

        foreach ($definition->getArguments() as $argument) {
            $arguments[] = [
                'name' => $argument->getName(),
                'description' => $argument->getDescription(),
                'required' => $argument->isRequired(),
                'default' => $argument->getDefault(),
            ];
        }

        return $arguments;
    }

    /**
     * Get command options.
     *
     * @param  \Illuminate\Console\Command  $command
     * @return array<string, mixed>
     */
    protected function getCommandOptions($command): array
    {
        $options = [];
        $definition = $command->getDefinition();

        foreach ($definition->getOptions() as $option) {
            $options[] = [
                'name' => $option->getName(),
                'shortcut' => $option->getShortcut(),
                'description' => $option->getDescription(),
                'accept_value' => $option->acceptValue(),
                'is_value_required' => $option->isValueRequired(),
                'is_multiple' => $option->isArray(),
                'default' => $option->getDefault(),
            ];
        }

        return $options;
    }

    /**
     * Generate log file path for command execution.
     *
     * @param  string  $command
     * @return string
     */
    protected function generateLogPath(string $command): string
    {
        $basePath = config('artisan-gui.log_storage_path', storage_path('logs/artisan-gui'));
        $filename = str_replace(':', '-', $command).'-'.now()->format('Y-m-d-His').'.log';

        return $basePath.'/'.$filename;
    }
}

