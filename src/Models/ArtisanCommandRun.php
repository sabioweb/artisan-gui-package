<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Models;

use Sabiowebcom\ArtisanGui\Database\Factories\ArtisanCommandRunFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtisanCommandRun extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artisan_command_runs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'command',
        'parameters',
        'status',
        'output',
        'error',
        'log_path',
        'exit_code',
        'executed_by',
        'started_at',
        'finished_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameters' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'exit_code' => 'integer',
    ];

    /**
     * Get the user who executed the command.
     *
     * @return BelongsTo
     */
    public function executor(): BelongsTo
    {
        $userModel = config('auth.providers.users.model', 'App\Models\User');

        return $this->belongsTo($userModel, 'executed_by');
    }

    /**
     * Check if command execution was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if command execution failed.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get execution duration in seconds.
     *
     * @return float|null
     */
    public function getDurationAttribute(): ?float
    {
        if (! $this->started_at || ! $this->finished_at) {
            return null;
        }

        return $this->started_at->diffInSeconds($this->finished_at, true);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return ArtisanCommandRunFactory::new();
    }
}

