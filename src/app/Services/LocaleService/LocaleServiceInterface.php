<?php

namespace App\Services\LocaleService;

interface LocaleServiceInterface {
    /**
     * Get a translation for a given key and locale.
     *
     * @param string $key
     * @param string|null $locale
     * @return string
     */
    public function translate(string $key, ?string $locale = null): string;

    /**
     * Set the application's locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale): void;

    /**
     * Get the application's current locale.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Get all translations for the current or specified locale.
     *
     * @param string|null $locale
     * @return array
     */
    public function getTranslations(?string $locale = null): array;
}
