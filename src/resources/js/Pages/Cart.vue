<script setup>
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Корзина" />
    
    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-2xl font-bold text-red-600">🍕 PizzaDel</Link>
                    </div>
                    <nav class="flex items-center space-x-4">
                        <Link href="/menu" class="text-gray-700 hover:text-red-600">
                            📋 Меню
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">Корзина</h1>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Список товаров в корзине -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">Ваши заказы</h2>
                            
                            <!-- Заглушка пустой корзины -->
                            <div class="text-center py-8">
                                <div class="text-6xl mb-4">🛒</div>
                                <p class="text-gray-600 text-lg">Ваша корзина пуста</p>
                                <Link 
                                    href="/menu" 
                                    class="mt-4 inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg"
                                >
                                    Перейти к меню
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Итого -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-xl font-semibold mb-4">Итого</h2>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>Товары:</span>
                                    <span>₽0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Доставка:</span>
                                    <span>₽150</span>
                                </div>
                                <div class="border-t pt-2">
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>К оплате:</span>
                                        <span class="text-red-600">₽150</span>
                                    </div>
                                </div>
                            </div>
                            <button 
                                class="w-full mt-6 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold disabled:bg-gray-400"
                                disabled
                            >
                                Оформить заказ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
