<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Tests\Unit;

use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Sabiowebcom\ArtisanGui\Services\ArtisanCommandService;
use Sabiowebcom\ArtisanGui\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtisanCommandServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ArtisanCommandService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ArtisanCommandService();
    }

    public function test_can_get_available_commands(): void
    {
        $commands = $this->service->getAvailableCommands();

        $this->assertIsArray($commands);
        $this->assertNotEmpty($commands);
    }

    public function test_can_execute_allowed_command(): void
    {
        config(['artisan-gui.allowed_commands' => ['cache:clear']]);

        $commandRun = $this->service->execute('cache:clear', [], null);

        $this->assertInstanceOf(ArtisanCommandRun::class, $commandRun);
        $this->assertDatabaseHas('artisan_command_runs', [
            'command' => 'cache:clear',
            'status' => 'success',
        ]);
    }

    public function test_rejects_non_allowed_command(): void
    {
        config(['artisan-gui.allowed_commands' => ['cache:clear']]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Command 'migrate' is not allowed.");

        $this->service->execute('migrate', [], null);
    }
}

