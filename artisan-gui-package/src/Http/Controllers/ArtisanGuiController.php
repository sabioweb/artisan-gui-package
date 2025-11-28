<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Http\Controllers;

use Sabiowebcom\ArtisanGui\Http\Requests\ExecuteCommandRequest;
use Sabiowebcom\ArtisanGui\Http\Resources\ArtisanCommandRunResource;
use Sabiowebcom\ArtisanGui\Http\Resources\CommandResource;
use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Sabiowebcom\ArtisanGui\Services\ArtisanCommandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtisanGuiController extends Controller
{
    public function __construct(
        protected ArtisanCommandService $commandService
    ) {
    }

    /**
     * Display the dashboard.
     */
    public function dashboard(): View
    {
        $recentRuns = ArtisanCommandRun::latest()
            ->limit(5)
            ->with('executor')
            ->get();

        $stats = [
            'total_runs' => ArtisanCommandRun::count(),
            'successful_runs' => ArtisanCommandRun::where('status', 'success')->count(),
            'failed_runs' => ArtisanCommandRun::where('status', 'failed')->count(),
            'recent_successful' => ArtisanCommandRun::where('status', 'success')
                ->where('created_at', '>=', now()->subDay())
                ->count(),
            'recent_failed' => ArtisanCommandRun::where('status', 'failed')
                ->where('created_at', '>=', now()->subDay())
                ->count(),
        ];

        return view('artisan-gui::dashboard', [
            'recentRuns' => $recentRuns,
            'stats' => $stats,
        ]);
    }

    /**
     * Display the run command page.
     */
    public function runCommand(): View
    {
        $commands = $this->commandService->getAvailableCommands();

        return view('artisan-gui::run-command', [
            'commands' => $commands,
        ]);
    }

    /**
     * Display the commands catalog.
     */
    public function catalog(): View
    {
        $commands = $this->commandService->getAvailableCommands();

        return view('artisan-gui::catalog', [
            'commands' => $commands,
        ]);
    }

    /**
     * Display the history page.
     */
    public function history(Request $request): View
    {
        $runs = ArtisanCommandRun::with('executor')
            ->latest()
            ->paginate(20);

        return view('artisan-gui::history', [
            'runs' => $runs,
        ]);
    }

    /**
     * Display the about page.
     */
    public function about(): View
    {
        return view('artisan-gui::about', [
            'version' => '1.0.0',
            'author' => [
                'name' => 'Art Team',
                'email' => 'dev@art.com',
            ],
            'project' => [
                'name' => 'Art Platform',
                'description' => 'Art and content management platform',
            ],
        ]);
    }

    /**
     * Execute a command via AJAX.
     */
    public function execute(ExecuteCommandRequest $request): JsonResponse
    {
        $command = $request->validated()['command'];
        $parameters = $request->validated()['parameters'] ?? [];

        $commandRun = $this->commandService->execute(
            $command,
            $parameters,
            $request->user()
        );

        return response()->json([
            'success' => $commandRun->isSuccessful(),
            'message' => $commandRun->isSuccessful()
                ? 'Command executed successfully'
                : 'Command execution failed',
            'data' => new ArtisanCommandRunResource($commandRun),
        ], $commandRun->isSuccessful() ? 200 : 422);
    }

    /**
     * Get available commands.
     */
    public function getCommands(): JsonResponse
    {
        $commands = $this->commandService->getAvailableCommands();

        return response()->json([
            'data' => collect($commands)->map(fn ($cmd) => new CommandResource($cmd)),
        ]);
    }

    /**
     * Get command execution details.
     */
    public function show(int $id): JsonResponse
    {
        $commandRun = ArtisanCommandRun::with('executor')->findOrFail($id);

        return response()->json([
            'data' => new ArtisanCommandRunResource($commandRun),
        ]);
    }

    /**
     * Download log file.
     */
    public function downloadLog(int $id)
    {
        $commandRun = ArtisanCommandRun::findOrFail($id);

        if (! $commandRun->log_path || ! file_exists($commandRun->log_path)) {
            abort(404, 'Log file not found');
        }

        return response()->download(
            $commandRun->log_path,
            basename($commandRun->log_path)
        );
    }
}

