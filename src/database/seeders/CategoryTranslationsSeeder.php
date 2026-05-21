<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'base' => [
                'en' => 'Base',
                'de' => 'Basis',
                'ua' => 'Основа',
            ],
            'sauce' => [
                'en' => 'Sauce',
                'de' => 'Soße',
                'ua' => 'Соус',
            ],
            'filling' => [
                'en' => 'Filling',
                'de' => 'Füllung',
                'ua' => 'Начинка',
            ],
        ];

        foreach ($categories as $category => $translations) {
            foreach ($translations as $locale => $value) {
                Translation::updateOrCreate(
                    [
                        'translatable_type' => 'ingredient_category',
                        'translatable_id' => 0,
                        'locale' => $locale,
                        'key' => $category,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }
}
