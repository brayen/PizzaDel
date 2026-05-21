export const dictionaryConfig = {
    ingredients: {
        translationKey: 'ingredients.title',
        translationContext: 'ingredients',
        displayOrder: ['name', 'description', 'calories_per_gram', 'is_active'],
    },
    pizzas: {
        translationKey: 'pizzas.title',
        translationContext: 'pizzas',
        displayOrder: ['name', 'description', 'base_ingredient_id', 'sauce_ingredient_id', 'spiciness', 'is_vegan', 'price', 'selling_price', 'is_active'],
    },
};

export const getDictionaryConfig = (dictionary) => {
    return dictionaryConfig[dictionary] || null;
};
