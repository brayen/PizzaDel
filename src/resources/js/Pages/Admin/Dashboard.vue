<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Админ панель" />
    
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-red-600">🍕 PizzaDel Admin</h1>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h2 class="text-xl font-semibold mb-4">Панель администратора</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Заказы</h3>
                        <p class="mt-2 text-3xl font-bold text-red-600">0</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Пиццы</h3>
                        <p class="mt-2 text-3xl font-bold text-green-600">0</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Клиенты</h3>
                        <p class="mt-2 text-3xl font-bold text-blue-600">0</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900">Доход</h3>
                        <p class="mt-2 text-3xl font-bold text-purple-600">₽0</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
