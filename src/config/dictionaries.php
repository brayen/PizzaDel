<?php

return [
    'ingredients' => [
        'model' => \App\Models\Ingredient::class,
        'fields' => [
            'category' => ['type' => 'select', 'required' => true, 'label' => 'Category', 'options' => ['base' => 'Base', 'sauce' => 'Sauce', 'filling' => 'Filling']],
            'name' => ['type' => 'text', 'required' => true, 'label' => 'Name'],
            'description' => ['type' => 'textarea', 'required' => false, 'label' => 'Description'],
            'calories_per_gram' => ['type' => 'number', 'required' => false, 'label' => 'Calories per 100g'],
            'is_active' => ['type' => 'boolean', 'required' => false, 'label' => 'Active'],
        ],
    ],
    'pizzas' => [
        'model' => \App\Models\Pizza::class,
        'fields' => [
            'name' => ['type' => 'text', 'required' => true, 'label' => 'Name'],
            'description' => ['type' => 'textarea', 'required' => false, 'label' => 'Description'],
            'base_ingredient_id' => ['type' => 'select', 'required' => true, 'label' => 'Base', 'relation' => 'ingredients', 'filter' => ['category' => 'base']],
            'sauce_ingredient_id' => ['type' => 'select', 'required' => true, 'label' => 'Sauce', 'relation' => 'ingredients', 'filter' => ['category' => 'sauce']],
            'spiciness' => ['type' => 'select', 'required' => true, 'label' => 'Spiciness', 'options' => ['not_spicy' => 'Not spicy', 'moderately_spicy' => 'Moderately spicy', 'spicy' => 'Spicy', 'very_spicy' => 'Very spicy']],
            'is_vegan' => ['type' => 'boolean', 'required' => false, 'label' => 'Vegan'],
            'price' => ['type' => 'number', 'required' => false, 'label' => 'Price'],
            'selling_price' => ['type' => 'number', 'required' => false, 'label' => 'Selling price'],
            'is_active' => ['type' => 'boolean', 'required' => false, 'label' => 'Active'],
        ],
    ],
];
