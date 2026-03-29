<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Меню" />

    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-red-600">🍕 PizzaDel</Link>
                    </div>
                    <nav class="flex items-center space-x-4">
                        <Link href="/cart" class="text-gray-700 hover:text-red-600">
                            🛒 Корзина
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Наше меню</h1>

                <!-- Категории -->
                <div class="mb-8">
                    <div class="flex space-x-4">
                        <button class="bg-red-600 text-white px-4 py-2 rounded-lg">Все</button>
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Мясные</button>
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Вегетарианские</button>
                        <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Острые</button>
                    </div>
                </div>

                <!-- Сетка пиццы -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Пример карточки пиццы -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="/pizza-placeholder.jpg" alt="Пицца" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Маргарита</h3>
                            <p class="mt-1 text-gray-600">Томатный соус, моцарелла, базилик</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-2xl font-bold text-red-600">€15</span>
                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Заглушки для других пицц -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Пицца 2</span>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900">Пепперони</h3>
                            <p class="mt-1 text-gray-600">Томатный соус, моцарелла, пепперони</p>
                            <div class="mt-4 flex justify-between items-center">
                                <span class="text-2xl font-bold text-red-600">€22</span>
                                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    В корзину
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
