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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 overflow-x-auto">
            <!-- Hero Section -->
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                    {{ t('common.welcome') }}
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 mb-6 sm:mb-8">
                    {{ t('common.pizza_delivery') }}
                </p>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <Link
                        href="/products"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 sm:px-8 py-3 rounded-lg text-base sm:text-lg font-medium transition-colors flex-shrink-0"
                    >
                        {{ t('common.order_online') }}
                    </Link>
                    <Link
                        href="/register"
                        class="border border-emerald-600 text-emerald-600 hover:bg-emerald-50 px-6 sm:px-8 py-3 rounded-lg text-base sm:text-lg font-medium transition-colors flex-shrink-0"
                    >
                        {{ t('navigation.auth.register') }}
                    </Link>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-12 sm:mt-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 overflow-x-auto">
                <div class="text-center flex-shrink-0">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">🚀</div>
                    <h3 class="text-base sm:text-lg font-semibold">{{ t('common.fast_delivery') }}</h3>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">{{ t('common.fast_delivery_desc') }}</p>
                </div>
                <div class="text-center flex-shrink-0">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">👨‍🍳</div>
                    <h3 class="text-base sm:text-lg font-semibold">{{ t('common.best_chefs') }}</h3>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">{{ t('common.best_chefs_desc') }}</p>
                </div>
                <div class="text-center flex-shrink-0">
                    <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">💳</div>
                    <h3 class="text-base sm:text-lg font-semibold">{{ t('common.easy_payment') }}</h3>
                    <p class="mt-2 text-sm sm:text-base text-gray-600">{{ t('common.easy_payment_desc') }}</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
