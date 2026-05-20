<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const t = (key) => localeStore.t(key);

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const userName = computed(() => props.auth.user.name || t('common.customer'));
const loyaltyPoints = computed(() => props.auth.user.preferences?.loyalty_points || 0);
const loyaltyPointsEuros = computed(() => (loyaltyPoints.value / 100).toFixed(2));
const discountLevel = computed(() => props.auth.user.preferences?.discount_level || 'bronze');

onMounted(() => {
    localeStore.setContext('public');
});
</script>

<template>
    <AppLayout>
        <Head :title="t('messages.customer_dashboard')" />
        
        <div class="min-h-screen bg-gray-100">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <Link href="/" class="flex items-center">
                                <h1 class="text-2xl font-bold text-emerald-600">🍕 PizzaDel</h1>
                            </Link>
                            <span class="ml-4 text-sm text-gray-500">{{ t('messages.customer_portal') }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">
                                {{ t('common.welcome') }}, {{ userName }}
                            </span>
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                class="text-gray-700 hover:text-emerald-600 px-3 py-2 rounded-md text-sm font-medium"
                            >
                                {{ t('common.logout') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">{{ t('messages.welcome_back') }}, {{ userName }}!</h2>
                        <p class="mt-2 text-gray-600">{{ t('messages.manage_orders_preferences') }}</p>
                    </div>

                    <!-- Loyalty Status -->
                    <div class="bg-white shadow rounded-lg mb-8">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ t('messages.loyalty_status') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-emerald-50 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-emerald-800">{{ t('messages.loyalty_points') }}</div>
                                    <div class="text-2xl font-bold text-emerald-900">{{ loyaltyPoints }} ({{ t('common.currency') }}{{ loyaltyPointsEuros }})</div>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-blue-800">{{ t('messages.discount_level') }}</div>
                                    <div class="text-2xl font-bold text-blue-900 capitalize">{{ discountLevel }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <Link
                            href="/menu"
                            class="bg-white hover:bg-gray-50 p-6 rounded-lg shadow text-center"
                        >
                            <div class="text-3xl mb-3">🍕</div>
                            <h3 class="text-lg font-medium text-gray-900">{{ t('messages.order_pizza') }}</h3>
                            <p class="mt-2 text-gray-600">{{ t('messages.browse_menu') }}</p>
                        </Link>

                        <Link
                            href="/orders"
                            class="bg-white hover:bg-gray-50 p-6 rounded-lg shadow text-center"
                        >
                            <div class="text-3xl mb-3">📦</div>
                            <h3 class="text-lg font-medium text-gray-900">{{ t('messages.my_orders') }}</h3>
                            <p class="mt-2 text-gray-600">{{ t('messages.view_order_history') }}</p>
                        </Link>

                        <Link
                            href="/profile"
                            class="bg-white hover:bg-gray-50 p-6 rounded-lg shadow text-center"
                        >
                            <div class="text-3xl mb-3">👤</div>
                            <h3 class="text-lg font-medium text-gray-900">{{ t('messages.my_profile') }}</h3>
                            <p class="mt-2 text-gray-600">{{ t('messages.manage_account') }}</p>
                        </Link>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ t('messages.recent_orders') }}
                            </h3>
                            <div class="text-center text-gray-500 py-8">
                                <div class="text-4xl mb-3">📋</div>
                                <p>{{ t('messages.no_orders_yet') }}</p>
                                <Link
                                    href="/menu"
                                    class="mt-4 inline-flex text-emerald-600 hover:text-emerald-500"
                                >
                                    {{ t('messages.place_first_order') }} →
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>
