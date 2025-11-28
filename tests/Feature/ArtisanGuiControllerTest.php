<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Tests\Feature;

use Sabiowebcom\ArtisanGui\Models\ArtisanCommandRun;
use Sabiowebcom\ArtisanGui\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtisanGuiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_page_loads(): void
    {
        $response = $this->get(route('artisan-gui.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('artisan-gui::dashboard');
    }

    public function test_run_command_page_loads(): void
    {
        $response = $this->get(route('artisan-gui.run'));

        $response->assertStatus(200);
        $response->assertViewIs('artisan-gui::run-command');
    }

    public function test_catalog_page_loads(): void
    {
        $response = $this->get(route('artisan-gui.catalog'));

        $response->assertStatus(200);
        $response->assertViewIs('artisan-gui::catalog');
    }

    public function test_history_page_loads(): void
    {
        $response = $this->get(route('artisan-gui.history'));

        $response->assertStatus(200);
        $response->assertViewIs('artisan-gui::history');
    }

    public function test_about_page_loads(): void
    {
        $response = $this->get(route('artisan-gui.about'));

        $response->assertStatus(200);
        $response->assertViewIs('artisan-gui::about');
    }

    public function test_can_execute_command_via_api(): void
    {
        config(['artisan-gui.allowed_commands' => ['cache:clear']]);

        $response = $this->postJson(route('artisan-gui.api.execute'), [
            'command' => 'cache:clear',
            'parameters' => [],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'command',
                'status',
            ],
        ]);

        $this->assertDatabaseHas('artisan_command_runs', [
            'command' => 'cache:clear',
        ]);
    }

    public function test_can_get_commands_list(): void
    {
        $response = $this->getJson(route('artisan-gui.api.commands'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'description',
                ],
            ],
        ]);
    }

    public function test_can_get_command_run_details(): void
    {
        $commandRun = ArtisanCommandRun::factory()->create();

        $response = $this->getJson(route('artisan-gui.api.runs.show', $commandRun->id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'command',
                'status',
            ],
        ]);
    }
}

