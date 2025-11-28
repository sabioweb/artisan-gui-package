<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Console\Commands;

use Illuminate\Console\Command;
use Sabiowebcom\ArtisanGui\Services\TranslationSyncService;

class TranslateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artisan-gui:translate 
                            {--lang= : Specific language to translate (optional)}
                            {--force : Overwrite existing translations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate translations for Artisan GUI package using auto-translation API';

    /**
     * Execute the console command.
     */
    public function handle(TranslationSyncService $service): int
    {
        if (! config('artisan-gui.auto_translation.enabled', false)) {
            $this->error('Auto-translation is disabled.');
            $this->info('Enable it in config/artisan-gui.php or set ARTISAN_GUI_AUTO_TRANSLATE=true');

            return Command::FAILURE;
        }

        $this->info('Starting translation generation...');

        try {
            $results = $service->generateTranslations();

            $this->info('Translation completed!');
            $this->table(
                ['Language', 'Translated Keys'],
                collect($results)->map(fn ($count, $lang) => [$lang, $count])->toArray()
            );

            $this->warn('Please review the generated translations for accuracy.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Translation failed: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}

