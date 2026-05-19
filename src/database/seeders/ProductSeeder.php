<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'PIZZA-001',
                'slug' => 'margherita',
                'base_price' => 1299, // €12.99
                'translations' => [
                    'en' => [
                        'name' => 'Margherita Pizza',
                        'description' => 'Classic Italian pizza with fresh mozzarella, tomatoes, and basil. A timeless favorite.',
                        'meta_title' => 'Margherita Pizza - Authentic Italian Recipe | PizzaDel',
                        'meta_description' => 'Order authentic Margherita pizza with fresh mozzarella, tomatoes, and basil. Made with traditional Italian recipe.',
                    ],
                    'ua' => [
                        'name' => 'Піца Маргарита',
                        'description' => 'Класична італійська піца зі свіжим моцарелою, томатами та базиліком. Вічна класика.',
                        'meta_title' => 'Піца Маргарита - Автентичний італійський рецепт | PizzaDel',
                        'meta_description' => 'Замовте справжню піцу Маргарита зі свіжим моцарелою, томатами та базиліком. Приготована за традиційним італійським рецептом.',
                    ],
                    'de' => [
                        'name' => 'Margherita-Pizza',
                        'description' => 'Klassische italienische Pizza mit frischem Mozzarella, Tomaten und Basilikum. Ein zeitloser Favorit.',
                        'meta_title' => 'Margherita-Pizza - Authentisches italienisches Rezept | PizzaDel',
                        'meta_description' => 'Bestellen Sie authentische Margherita-Pizza mit frischem Mozzarella, Tomaten und Basilikum. Nach traditionellem italienischem Rezept zubereitet.',
                    ],
                ],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'sku' => 'PIZZA-002',
                'slug' => 'pepperoni',
                'base_price' => 1499, // €14.99
                'translations' => [
                    'en' => [
                        'name' => 'Pepperoni Pizza',
                        'description' => 'Spicy pepperoni with mozzarella cheese and our signature tomato sauce. Perfect for those who love a kick.',
                        'meta_title' => 'Pepperoni Pizza - Spicy & Delicious | PizzaDel',
                        'meta_description' => 'Order spicy pepperoni pizza with mozzarella cheese and signature tomato sauce. A perfect choice for spice lovers.',
                    ],
                    'ua' => [
                        'name' => 'Піца Пепероні',
                        'description' => 'Гостра пепероні з моцарелою та нашим особливим томатним соусом. Ідеально для тих, хто любить гостре.',
                        'meta_title' => 'Піца Пепероні - Гостра та смачна | PizzaDel',
                        'meta_description' => 'Замовте гостру піцу пепероні з моцарелою та особливим томатним соусом. Ідеальний вибір для любителів гострого.',
                    ],
                    'de' => [
                        'name' => 'Pepperoni-Pizza',
                        'description' => 'Scharfe Pepperoni mit Mozzarella Käse und unserer signatur Tomatensauce. Perfekt für diejenigen, die es scharf mögen.',
                        'meta_title' => 'Pepperoni-Pizza - Scharf & Lecker | PizzaDel',
                        'meta_description' => 'Bestellen Sie scharfe Pepperoni-Pizza mit Mozzarella Käse und signatur Tomatensauce. Eine perfekte Wahl für Liebhaber von scharfem Essen.',
                    ],
                ],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'sku' => 'PIZZA-003',
                'slug' => 'hawaiian',
                'base_price' => 1399, // €13.99
                'translations' => [
                    'en' => [
                        'name' => 'Hawaiian Pizza',
                        'description' => 'Tropical combination of ham, pineapple, and mozzarella cheese. A sweet and savory delight.',
                        'meta_title' => 'Hawaiian Pizza - Tropical Taste | PizzaDel',
                        'meta_description' => 'Order Hawaiian pizza with ham, pineapple, and mozzarella cheese. Experience the perfect blend of sweet and savory.',
                    ],
                    'ua' => [
                        'name' => 'Гавайська піца',
                        'description' => 'Тропічна комбінація шинки, ананасу та моцарели. Солодке та солоне задоволення.',
                        'meta_title' => 'Гавайська піца - Тропічний смак | PizzaDel',
                        'meta_description' => 'Замовте гавайську піцу зі шинкою, ананасом та моцарелою. Насолоджуйтесь ідеальним поєднанням солодкого та солоного.',
                    ],
                    'de' => [
                        'name' => 'Hawaii-Pizza',
                        'description' => 'Tropische Kombination aus Schinken, Ananas und Mozzarella Käse. Ein süßlich-herzhafter Genuss.',
                        'meta_title' => 'Hawaii-Pizza - Tropischer Geschmack | PizzaDel',
                        'meta_description' => 'Bestellen Sie Hawaii-Pizza mit Schinken, Ananas und Mozzarella Käse. Erleben Sie die perfekte Kombination aus süß und herzhaft.',
                    ],
                ],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
