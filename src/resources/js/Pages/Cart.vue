<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const t = (key) => localeStore.t(key);

onMounted(() => {
    localeStore.setContext('public');
});
</script>

<template>
    <Head :title="t('cart.title')" />

    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-red-600">🍕 PizzaDel</Link>
                    </div>
                    <nav class="flex items-center space-x-4">
                        <Link href="/menu" class="text-gray-700 hover:text-red-600">
                            📋 {{ t('cart.menu') }}
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ t('cart.title') }}</h1>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Список товаров в корзине -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">{{ t('cart.your_orders') }}</h2>

                            <!-- Заглушка пустой корзины -->
                            <div class="text-center py-8">
                                <div class="text-6xl mb-4">🛒</div>
                                <p class="text-gray-600 text-lg">{{ t('cart.cart_empty') }}</p>
                                <Link
                                    href="/menu"
                                    class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg"
                                >
                                    {{ t('cart.go_to_menu') }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Итого -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">{{ t('cart.total') }}</h2>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>{{ t('cart.items') }}:</span>
                                    <span>{{ t('common.currency') }}0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ t('cart.delivery') }}:</span>
                                    <span>{{ t('common.currency') }}5</span>
                                </div>
                                <div class="border-t pt-2">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>{{ t('cart.to_pay') }}:</span>
                                        <span class="text-red-600">{{ t('common.currency') }}5</span>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold disabled:bg-gray-400"
                                disabled
                            >
                                {{ t('cart.checkout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
