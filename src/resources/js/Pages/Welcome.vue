<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();

// Initialize locale store
onMounted(() => {
    localeStore.initialize();
});

// Get translations
const t = (key, params = {}) => localeStore.t(key, params);

</script>

<template>
    <AppLayout>
        <Head title="Добро пожаловать в PizzaDel" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Hero Section -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ t('common.welcome') }}
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    {{ t('common.pizza_delivery') }}
                </p>
                
                <div class="space-x-4">
                    <Link
                        href="/products"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg text-lg font-medium"
                    >
                        {{ t('common.order_online') }}
                    </Link>
                    <Link
                        href="/register"
                        class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-8 py-3 rounded-lg text-lg font-medium"
                    >
                        {{ t('navigation.auth.register') }}
                    </Link>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl mb-4">🚀</div>
                    <h3 class="text-lg font-semibold">{{ t('common.fast_delivery') }}</h3>
                    <p class="mt-2 text-gray-600">{{ t('common.fast_delivery_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">👨‍🍳</div>
                    <h3 class="text-lg font-semibold">{{ t('common.best_chefs') }}</h3>
                    <p class="mt-2 text-gray-600">{{ t('common.best_chefs_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="text-4xl mb-4">💳</div>
                    <h3 class="text-lg font-semibold">{{ t('common.easy_payment') }}</h3>
                    <p class="mt-2 text-gray-600">{{ t('common.easy_payment_desc') }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
