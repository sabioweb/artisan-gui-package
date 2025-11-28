<?php

declare(strict_types=1);

use Sabiowebcom\ArtisanGui\Http\Controllers\ArtisanGuiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Artisan GUI Routes
|--------------------------------------------------------------------------
|
| These routes are wrapped with the 'artisan-gui' prefix and the 'artisan-gui.'
| name prefix by the Service Provider so they never collide with the host app.
|
| Final URLs look like: /artisan-gui/...
| Final route names look like: artisan-gui.dashboard, artisan-gui.run, etc.
|
*/

Route::get('/', [ArtisanGuiController::class, 'dashboard'])->name('dashboard');
Route::get('/run', [ArtisanGuiController::class, 'runCommand'])->name('run');
Route::get('/catalog', [ArtisanGuiController::class, 'catalog'])->name('catalog');
Route::get('/history', [ArtisanGuiController::class, 'history'])->name('history');
Route::get('/about', [ArtisanGuiController::class, 'about'])->name('about');

// API routes
Route::prefix('api')->group(function () {
    Route::post('/execute', [ArtisanGuiController::class, 'execute'])->name('api.execute');
    Route::get('/commands', [ArtisanGuiController::class, 'getCommands'])->name('api.commands');
    Route::get('/runs/{id}', [ArtisanGuiController::class, 'show'])->name('api.runs.show');
    Route::get('/runs/{id}/log', [ArtisanGuiController::class, 'downloadLog'])->name('api.runs.log');
});

