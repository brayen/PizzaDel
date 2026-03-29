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
                    
                    <!-- Menu Link -->
                    <Link
                        href="/products"
                        class="ml-8 text-emerald-600 hover:text-emerald-700 px-3 py-2 rounded-md text-sm font-medium"
                    >
                        {{ t('navigation.menu') }}
                    </Link>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Language Switcher -->
                    <LanguageSwitcher />

                    <!-- Auth Links -->
                    <template v-if="$page.props.auth.user">
                        <Link
                            href="/dashboard"
                            class="text-emerald-600 hover:text-emerald-700 px-3 py-2 rounded-md text-sm font-medium"
                        >
                            {{ t('navigation.auth.my_cabinet') }}
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium"
                        >
                            {{ t('navigation.auth.logout') }}
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            href="/login"
                            class="text-emerald-600 hover:text-emerald-700 px-3 py-2 rounded-md text-sm font-medium"
                        >
                            {{ t('navigation.auth.login') }}
                        </Link>
                        <Link
                            href="/register"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            {{ t('navigation.auth.register') }}
                        </Link>
                    </template>
                </div>
            </div>
        </nav>
    </header>
</template>
