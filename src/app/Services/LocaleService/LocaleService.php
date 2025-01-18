<?php

namespace App\Services\LocaleService;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\LocaleService\LocaleServiceInterface;

class LocaleService implements LocaleServiceInterface {

    protected string $fallbackLocale;
    protected int $cacheDuration; // in minutes

    public function __construct() {
        $this->fallbackLocale = config('app.fallback_locale', 'en');
        $this->cacheDuration = config('translation.cache_duration', 30);
    }

    /**
     * Get a translation for a given key and locale.
     *
     * @param string $key
     * @param string|null $locale
     * @return string
     */
    public function translate(string $key, ?string $locale = null): string {
        $locale = $locale ?? $this->getLocale();

        // Check if the translation exists in files
        if (Lang::has($key, $locale)) {
            return Lang::get($key, [], $locale);
        }

        // Check if the translation exists in the database
        $translation = $this->getTranslationFromDatabase($key, $locale);
        if ($translation !== null) {
            return $translation;
        }

        // Fallback to default locale if translation is not found
        return $this->fallbackTranslation($key);
    }

    /**
     * Fetch a translation from the database.
     *
     * @param string $key
     * @param string $locale
     * @return string|null
     */
    protected function getTranslationFromDatabase(string $key, string $locale): ?string {
        return Cache::remember(
            $this->generateCacheKey($key, $locale),
            now()->addMinutes($this->cacheDuration),
            function () use ($key, $locale) {
                return DB::table('translations')
                    ->where('key', $key)
                    ->where('locale', $locale)
                    ->value('value');
            }
        );
    }

    /**
     * Generate a unique cache key for translations.
     *
     * @param string $key
     * @param string $locale
     * @return string
     */
    protected function generateCacheKey(string $key, string $locale): string {
        return "translation_{$locale}_{$key}";
    }

    /**
     * Fallback to the default locale translation.
     *
     * @param string $key
     * @return string
     */
    protected function fallbackTranslation(string $key): string {
        if (Lang::has($key, $this->fallbackLocale)) {
            return Lang::get($key, [], $this->fallbackLocale);
        }

        // Return the key itself if no translation is found
        return $key;
    }

    /**
     * Set the application's locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void {
        // Session::put('locale', $locale);
        App::setLocale($locale);
    }

    /**
     * Get the application's current locale.
     *
     * @return string
     */
    public function getLocale(): string {
        // return Session::get('locale', Config::get('app.fallback_locale', 'en'));
        return App::getLocale();
    }

    /**
     * Get all translations for the current or specified locale.
     *
     * @param string|null $locale
     * @return array
     */
    public function getTranslations(?string $locale = null): array {
        $locale = $locale ?? $this->getLocale();
        $path = resource_path("lang/{$locale}.json");

        if (file_exists($path)) {
            try {
                return json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error('Translation JSON decoding error: ' . $e->getMessage());
            }
        }

        return [];
    }
}
