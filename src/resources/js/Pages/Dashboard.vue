<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Панель управления" />
    
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-red-600">🍕 PizzaDel</h1>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">Панель управления</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 flex items-center justify-center">
                    <div class="text-center">
                        <h2 class="text-2xl font-semibold text-gray-900">Добро пожаловать в панель управления</h2>
                        <p class="mt-2 text-gray-600">Здесь будет функционал для управления пиццерией</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
