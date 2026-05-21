<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import StaffSidebar from '@/Components/Staff/Sidebar.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import IngredientsTemplate from './ingredients/IngredientsTemplate.vue';
import PizzasTemplate from './pizzas/PizzasTemplate.vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const props = defineProps({
    dictionary: {
        type: String,
        default: 'ingredients',
    },
    auth: {
        type: Object,
        required: true
    }
});

const userRole = computed(() => props.auth.user.getRoleNames?.()?.[0] || 'staff');
const userName = computed(() => props.auth.user.name || 'Staff Member');

const dictionaryComponents = {
    ingredients: IngredientsTemplate,
    pizzas: PizzasTemplate,
};

const currentComponent = computed(() => {
    return dictionaryComponents[props.dictionary] || IngredientsTemplate;
});
</script>

<template>
    <component :is="currentComponent" :auth="auth" />
</template>
