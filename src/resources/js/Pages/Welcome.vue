<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Добро пожаловать в PizzaDel" />

    <div class="min-h-screen bg-gray-100">
        <header class="bg-white shadow">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-emerald-600">🍕 PizzaDel</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <Link
                            href="/login"
                            class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium"
                        >
                            Войти
                        </Link>
                        <Link
                            href="/register"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Регистрация
                        </Link>
                    </div>
                </div>
            </nav>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="text-center">
                    <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                        Доставка вкуснейшей пиццы
                    </h2>
                    <p class="mt-4 text-xl text-gray-600">
                        Заказывайте онлайн и получайте доставку за 30 минут
                    </p>
                    <div class="mt-8">
                        <Link
                            href="/menu"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-semibold px-8 py-3 rounded-lg"
                        >
                            Перейти к меню
                        </Link>
                    </div>
                </div>

                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-4xl mb-4">🚀</div>
                        <h3 class="text-lg font-semibold">Быстрая доставка</h3>
                        <p class="mt-2 text-gray-600">Доставляем за 30 минут или бесплатно</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-4">👨‍🍳</div>
                        <h3 class="text-lg font-semibold">Лучшие повара</h3>
                        <p class="mt-2 text-gray-600">Итальянские рецепты и свежие ингредиенты</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl mb-4">💳</div>
                        <h3 class="text-lg font-semibold">Удобная оплата</h3>
                        <p class="mt-2 text-gray-600">Наличные, карты или онлайн оплата</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
