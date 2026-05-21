<script setup>
import { Link } from '@inertiajs/vue3';
import StaffSidebar from '@/Components/Staff/Sidebar.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        default: ''
    },
    user: {
        type: Object,
        required: true
    }
});

const userRole = props.user.getRoleNames?.()?.[0] || 'staff';
const userName = props.user.name || 'Staff Member';
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <StaffSidebar />

        <div class="flex-1">
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-red-600">
                                {{ icon }} {{ title }}
                            </h1>
                        </div>
                        <div class="flex items-center space-x-4">
                            <LanguageSwitcher />
                            <span class="text-sm text-gray-700">
                                {{ userName }} ({{ userRole }})
                            </span>
                            <Link
                                href="/staff/logout"
                                method="post"
                                as="button"
                                class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium"
                            >
                                Logout
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
