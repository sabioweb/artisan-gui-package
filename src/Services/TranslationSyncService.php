<?php

declare(strict_types=1);

namespace Sabiowebcom\ArtisanGui\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TranslationSyncService
{
    protected string $baseLangPath;
    protected string $provider;
    protected ?string $apiKey;
    protected array $targetLanguages;

    public function __construct()
    {
        $this->baseLangPath = __DIR__.'/../../resources/lang';
        $this->provider = config('artisan-gui.auto_translation.provider', 'google');
        $this->apiKey = config('artisan-gui.auto_translation.api_key');
        $this->targetLanguages = explode(',', config('artisan-gui.auto_translation.target_languages', 'fa,ar,es,fr,de'));
    }

    /**
     * Generate translations for all target languages.
     *
     * @return array<string, int>
     */
    public function generateTranslations(): array
    {
        if (! config('artisan-gui.auto_translation.enabled', false)) {
            throw new \RuntimeException('Auto-translation is disabled. Enable it in config first.');
        }

        if (! $this->apiKey) {
            throw new \RuntimeException('Translation API key is not configured.');
        }

        $sourceFile = $this->baseLangPath.'/en/messages.php';
        if (! File::exists($sourceFile)) {
            throw new \RuntimeException('Source language file not found: '.$sourceFile);
        }

        $sourceTranslations = require $sourceFile;
        $results = [];

        foreach ($this->targetLanguages as $lang) {
            $lang = trim($lang);
            $results[$lang] = $this->translateFile($sourceTranslations, $lang);
        }

        return $results;
    }

    /**
     * Translate a single language file.
     *
     * @param  array<string, mixed>  $translations
     * @param  string  $targetLang
     * @return int Number of translated keys
     */
    protected function translateFile(array $translations, string $targetLang): int
    {
        $targetDir = $this->baseLangPath.'/'.$targetLang;
        File::ensureDirectoryExists($targetDir);

        $translated = $this->translateArray($translations, 'en', $targetLang);
        $targetFile = $targetDir.'/messages.php';

        $content = "<?php\n\ndeclare(strict_types=1);\n\nreturn ".$this->arrayToPhpCode($translated).";\n";
        File::put($targetFile, $content);

        return count($this->flattenArray($translated));
    }

    /**
     * Recursively translate an array of strings.
     *
     * @param  array<string, mixed>  $array
     * @param  string  $sourceLang
     * @param  string  $targetLang
     * @return array<string, mixed>
     */
    protected function translateArray(array $array, string $sourceLang, string $targetLang): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = $this->translateArray($value, $sourceLang, $targetLang);
            } elseif (is_string($value)) {
                $result[$key] = $this->translateText($value, $sourceLang, $targetLang);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Translate a single text string.
     *
     * @param  string  $text
     * @param  string  $sourceLang
     * @param  string  $targetLang
     * @return string
     */
    protected function translateText(string $text, string $sourceLang, string $targetLang): string
    {
        if (empty(trim($text))) {
            return $text;
        }

        try {
            return match ($this->provider) {
                'google' => $this->translateWithGoogle($text, $sourceLang, $targetLang),
                'deepl' => $this->translateWithDeepL($text, $sourceLang, $targetLang),
                default => throw new \RuntimeException("Unknown translation provider: {$this->provider}"),
            };
        } catch (\Exception $e) {
            // Log error but continue
            \Log::warning("Translation failed for text: {$text}", [
                'error' => $e->getMessage(),
                'provider' => $this->provider,
            ]);

            return $text; // Return original text on failure
        }
    }

    /**
     * Translate using Google Translate API (placeholder - requires actual API).
     *
     * @param  string  $text
     * @param  string  $sourceLang
     * @param  string  $targetLang
     * @return string
     */
    protected function translateWithGoogle(string $text, string $sourceLang, string $targetLang): string
    {
        // Placeholder implementation
        // In production, use Google Cloud Translation API or similar
        // Example: https://cloud.google.com/translate/docs/basic/translating-text

        // For now, return a placeholder indicating translation needed
        return "[TRANSLATE:{$targetLang}] {$text}";
    }

    /**
     * Translate using DeepL API (placeholder - requires actual API).
     *
     * @param  string  $text
     * @param  string  $sourceLang
     * @param  string  $targetLang
     * @return string
     */
    protected function translateWithDeepL(string $text, string $sourceLang, string $targetLang): string
    {
        // Placeholder implementation
        // In production, use DeepL API
        // Example: https://www.deepl.com/docs-api

        return "[TRANSLATE:{$targetLang}] {$text}";
    }

    /**
     * Convert array to PHP code string.
     *
     * @param  array<string, mixed>  $array
     * @param  int  $indent
     * @return string
     */
    protected function arrayToPhpCode(array $array, int $indent = 0): string
    {
        $spaces = str_repeat('    ', $indent);
        $lines = ["[\n"];

        foreach ($array as $key => $value) {
            $keyStr = is_string($key) && ! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)
                ? var_export($key, true)
                : $key;

            if (is_array($value)) {
                $lines[] = $spaces."    {$keyStr} => ".$this->arrayToPhpCode($value, $indent + 1).",\n";
            } else {
                $valueStr = var_export($value, true);
                $lines[] = $spaces."    {$keyStr} => {$valueStr},\n";
            }
        }

        $lines[] = $spaces.']';

        return implode('', $lines);
    }

    /**
     * Flatten array to count keys.
     *
     * @param  array<string, mixed>  $array
     * @param  string  $prefix
     * @return array<string>
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix ? "{$prefix}.{$key}" : $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[] = $newKey;
            }
        }

        return $result;
    }
}

