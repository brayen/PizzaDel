<?php

namespace App\Models\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslations
{
    /**
     * Get all translations for the model.
     */
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Get a translation for a specific key and locale.
     */
    public function getTranslation(string $key, string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        // Try to get translation for requested locale
        $translation = $this->translations()
            ->where('locale', $locale)
            ->where('key', $key)
            ->value('value');

        if ($translation !== null) {
            return $translation;
        }

        // Fallback to default locale
        if ($locale !== $fallbackLocale) {
            $translation = $this->translations()
                ->where('locale', $fallbackLocale)
                ->where('key', $key)
                ->value('value');

            if ($translation !== null) {
                return $translation;
            }
        }

        return null;
    }

    /**
     * Set a translation for a specific key and locale.
     */
    public function setTranslation(string $key, string $locale, string $value): void
    {
        $this->translations()->updateOrCreate(
            [
                'locale' => $locale,
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );
    }

    /**
     * Delete a translation for a specific key and locale.
     */
    public function deleteTranslation(string $key, string $locale = null): void
    {
        $locale = $locale ?? app()->getLocale();

        $this->translations()
            ->where('locale', $locale)
            ->where('key', $key)
            ->delete();
    }

    /**
     * Get all translations as an array grouped by locale.
     */
    public function getAllTranslations(): array
    {
        return $this->translations()
            ->get()
            ->groupBy('locale')
            ->map(function ($translations) {
                return $translations->pluck('value', 'key')->toArray();
            })
            ->toArray();
    }

    /**
     * Save multiple translations at once.
     */
    public function saveTranslations(array $translations): void
    {
        foreach ($translations as $locale => $fields) {
            foreach ($fields as $key => $value) {
                $this->setTranslation($key, $locale, $value);
            }
        }
    }

    /**
     * Delete all translations for the model.
     */
    public function deleteAllTranslations(): void
    {
        $this->translations()->delete();
    }

    /**
     * Get the translatable attributes.
     */
    public function getTranslatableAttributes(): array
    {
        return property_exists($this, 'translatable') ? $this->translatable : [];
    }

    /**
     * Boot the trait.
     */
    protected static function bootHasTranslations(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                $model->translations()->forceDelete();
            } else {
                $model->translations()->delete();
            }
        });
    }
}
