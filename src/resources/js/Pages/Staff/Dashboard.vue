<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import StaffSidebar from '@/Components/Staff/Sidebar.vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const userRole = computed(() => props.auth.user.getRoleNames?.()?.[0] || 'staff');
const userName = computed(() => props.auth.user.name || 'Staff Member');

const t = (key, params = {}) => localeStore.t(key, params);

onMounted(() => {
    localeStore.setContext('staff');
});
</script>

<template>
    <Head :title="t('dashboard.title')" />

    <div class="flex min-h-screen bg-gray-100">
        <StaffSidebar />

        <div class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-red-600">{{ t('dashboard.title') }}</h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">
                                {{ userName }} ({{ userRole }})
                            </span>
                            <Link
                                href="/staff/logout"
                                method="post"
                                as="button"
                                class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium"
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
                        <h2 class="text-2xl font-bold text-gray-900">{{ t('dashboard.welcome') }}, {{ userName }}!</h2>
                        <p class="mt-2 text-gray-600">{{ t('dashboard.today') }}</p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="text-3xl">📦</div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ t('dashboard.new_orders') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">12</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="text-3xl">👥</div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ t('dashboard.active_staff') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">8</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="text-3xl">🍕</div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ t('dashboard.pizzas_today') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">45</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="text-3xl">💰</div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">{{ t('dashboard.revenue') }}</dt>
                                            <dd class="text-lg font-medium text-gray-900">{{ t('common.currency') }}{{ (1250 / 100).toFixed(2) }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role-specific content -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                {{ t('dashboard.quick_actions') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <Link
                                    href="/orders"
                                    class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-4 py-3 rounded-lg text-center font-medium"
                                >
                                    {{ t('dashboard.view_orders') }}
                                </Link>
                                <Link
                                    href="/menu/manage"
                                    class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-4 py-3 rounded-lg text-center font-medium"
                                >
                                    {{ t('dashboard.manage_menu') }}
                                </Link>
                                <Link
                                    href="/staff/manage"
                                    class="bg-purple-50 hover:bg-purple-100 text-purple-700 px-4 py-3 rounded-lg text-center font-medium"
                                >
                                    {{ t('dashboard.manage_staff') }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
