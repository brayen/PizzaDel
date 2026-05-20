<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const page = usePage();
const isCollapsed = ref(false);
const expandedMenus = ref({});

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};

const toggleMenu = (menuName) => {
    if (!isCollapsed.value) {
        expandedMenus.value[menuName] = !expandedMenus.value[menuName];
    }
};

const t = (key) => localeStore.t(key);

const menuItems = computed(() => [
    {
        name: t('common.dashboard'),
        icon: '📊',
        route: 'staff.dashboard',
        url: '/staff/dashboard',
    },
    {
        name: t('dictionaries.title'),
        icon: '📚',
        route: 'staff.dictionaries',
        url: '/staff/dictionaries',
        hasSubmenu: true,
        submenu: [
            {
                name: t('dictionaries.ingredients'),
                url: '/staff/dictionaries/ingredients',
            },
        ],
    },
    {
        name: t('sidebar.orders'),
        icon: '📦',
        route: null,
        url: '#',
    },
    {
        name: t('sidebar.pizzas'),
        icon: '🍕',
        route: null,
        url: '#',
    },
    {
        name: t('sidebar.customers'),
        icon: '👥',
        route: null,
        url: '#',
    },
    {
        name: t('sidebar.staff'),
        icon: '👨‍💼',
        route: null,
        url: '#',
    },
]);

const currentUrl = computed(() => page.url);

onMounted(() => {
    localeStore.setContext('staff');
    // Auto-expand dictionaries menu if on a dictionary page
    if (currentUrl.value.startsWith('/staff/dictionaries')) {
        expandedMenus.value[t('dictionaries.title')] = true;
    }
});
</script>

<template>
    <div
        :class="[
            'bg-white shadow-lg transition-all duration-300 ease-in-out',
            isCollapsed ? 'w-20' : 'w-64'
        ]"
        class="min-h-screen flex flex-col"
    >
        <!-- Logo / Toggle -->
        <div class="p-4 border-b border-gray-200" style="height: 65px">
            <div class="flex items-center justify-between">
                <div v-if="!isCollapsed" class="flex items-center space-x-2">
                    <span class="text-2xl">🍕</span>
                    <span class="text-xl font-bold text-red-600">PizzaDel</span>
                </div>
                <button
                    @click="toggleSidebar"
                    class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    :class="isCollapsed ? 'mx-auto' : ''"
                >
                    <svg
                        :class="isCollapsed ? 'rotate-180' : ''"
                        class="w-5 h-5 text-gray-600 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 21 21"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <template v-for="item in menuItems" :key="item.name">
                <!-- Menu item with submenu -->
                <div v-if="item.hasSubmenu">
                    <button
                        @click="toggleMenu(item.name)"
                        :class="[
                            'flex items-center w-full p-3 rounded-lg transition-colors',
                            currentUrl.startsWith(item.url)
                                ? 'bg-red-50 text-red-600'
                                : 'text-gray-700 hover:bg-gray-100'
                        ]"
                    >
                        <span class="text-xl flex-shrink-0">{{ item.icon }}</span>
                        <span
                            v-if="!isCollapsed"
                            class="ml-3 font-medium flex-1 text-left transition-opacity duration-300"
                        >
                            {{ item.name }}
                        </span>
                        <svg
                            v-if="!isCollapsed"
                            :class="[
                                'w-4 h-4 transition-transform duration-300',
                                expandedMenus[item.name] ? 'rotate-90' : ''
                            ]"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>
                    <!-- Submenu items -->
                    <div
                        v-if="!isCollapsed && expandedMenus[item.name]"
                        class="ml-8 mt-1 space-y-1"
                    >
                        <Link
                            v-for="subItem in item.submenu"
                            :key="subItem.url"
                            :href="subItem.url"
                            :class="[
                                'flex items-center p-2 rounded-lg transition-colors text-sm',
                                currentUrl === subItem.url
                                    ? 'bg-red-100 text-red-600'
                                    : 'text-gray-600 hover:bg-gray-100'
                            ]"
                        >
                            <span class="ml-2">{{ subItem.name }}</span>
                        </Link>
                    </div>
                </div>
                <!-- Regular menu item -->
                <Link
                    v-else
                    :href="item.url"
                    :class="[
                        'flex items-center p-3 rounded-lg transition-colors',
                        currentUrl === item.url || (item.url !== '#' && currentUrl.startsWith(item.url))
                            ? 'bg-red-50 text-red-600'
                            : 'text-gray-700 hover:bg-gray-100'
                    ]"
                >
                    <span class="text-xl flex-shrink-0">{{ item.icon }}</span>
                    <span
                        v-if="!isCollapsed"
                        class="ml-3 font-medium transition-opacity duration-300"
                    >
                        {{ item.name }}
                    </span>
                </Link>
            </template>
        </nav>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200">
            <div v-if="!isCollapsed" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 font-bold">A</span>
                </div>
                <div>
                    <div class="font-medium text-gray-900">Admin</div>
                    <div class="text-sm text-gray-500">admin@pizzadel.com</div>
                </div>
            </div>
            <div v-else class="flex justify-center">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 font-bold">A</span>
                </div>
            </div>
        </div>
    </div>
</template>
