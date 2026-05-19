<script setup>
import { Link } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
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
    <header class="bg-white shadow">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <Link href="/" class="text-2xl font-bold text-emerald-600">
                        🍕 PizzaDel
                    </Link>

                    <!-- Navigation Menu -->
                    <div class="hidden md:flex items-center space-x-8 ml-8">
                        <Link
                            href="/products"
                            class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            {{ t('navigation.menu') }}
                        </Link>

                        <Link
                            href="/about"
                            class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ t('navigation.main_nav.about') }}
                        </Link>

                        <Link
                            href="/contact"
                            class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ t('navigation.main_nav.contact') }}
                        </Link>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <LanguageSwitcher />

                    <!-- Auth Links -->
                    <template v-if="$page.props.auth.user">
                        <Link
                            href="/dashboard"
                            class="text-emerald-600 hover:text-emerald-700 p-2 rounded-md text-sm font-medium flex items-center gap-2"
                            title="{{ t('navigation.auth.my_cabinet') }}"
                        >
                            <i class="fas fa-user-circle"></i>
                            <span class="hidden sm:inline">{{ t('navigation.auth.my_cabinet') }}</span>
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="text-gray-700 hover:text-emerald-600 p-2 rounded-md text-sm font-medium flex items-center gap-2"
                            title="{{ t('navigation.auth.logout') }}"
                        >
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline">{{ t('navigation.auth.logout') }}</span>
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            href="/login"
                            class="text-emerald-600 hover:text-emerald-700 p-2 rounded-md text-sm font-medium flex items-center gap-2"
                            title="{{ t('navigation.auth.login') }}"
                        >
                            <i class="fas fa-sign-in-alt"></i>
                            <span class="hidden sm:inline">{{ t('navigation.auth.login') }}</span>
                        </Link>
                        <Link
                            href="/register"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white p-2 rounded-md text-sm font-medium flex items-center gap-2"
                            title="{{ t('navigation.auth.register') }}"
                        >
                            <i class="fas fa-user-plus"></i>
                            <span class="hidden sm:inline">{{ t('navigation.auth.register') }}</span>
                        </Link>
                    </template>
                </div>
            </div>
        </nav>
    </header>
</template>
