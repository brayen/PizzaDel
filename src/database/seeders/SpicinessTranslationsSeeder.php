<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpicinessTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spicinessLevels = [
            'not_spicy' => [
                'en' => 'Not spicy',
                'de' => 'Nicht scharf',
                'ua' => 'Не гостра',
            ],
            'moderately_spicy' => [
                'en' => 'Moderately spicy',
                'de' => 'Mäßig scharf',
                'ua' => 'Помірно гостра',
            ],
            'spicy' => [
                'en' => 'Spicy',
                'de' => 'Scharf',
                'ua' => 'Гостра',
            ],
            'very_spicy' => [
                'en' => 'Very spicy',
                'de' => 'Sehr scharf',
                'ua' => 'Дуже гостра',
            ],
        ];

        foreach ($spicinessLevels as $level => $translations) {
            foreach ($translations as $locale => $value) {
                Translation::updateOrCreate(
                    [
                        'translatable_type' => 'pizza_spiciness',
                        'translatable_id' => 0,
                        'locale' => $locale,
                        'key' => $level,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}
