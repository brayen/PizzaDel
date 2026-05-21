<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const t = (key) => localeStore.t(key);

defineProps({
    canRegister: Boolean,
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};

const isDisabled = computed(() => form.processing);

onMounted(() => {
    localeStore.setContext('public');
});
</script>

<template>
    <Head :title="t('auth.login')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-6 sm:py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 sm:space-y-8">
            <div>
                <Link href="/" class="flex justify-center items-center gap-2 text-emerald-600 hover:text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="text-xl sm:text-2xl lg:text-3xl font-bold">🍕 PizzaDel</span>
                </Link>
                <h2 class="mt-4 sm:mt-6 text-center text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900">
                    {{ t('auth.sign_in_to_account') }}
                </h2>
                <p class="mt-2 text-center text-xs sm:text-sm text-gray-600">
                    — <Link 
                        href="/register" 
                        class="font-medium text-emerald-600 hover:text-emerald-500"
                    >
                        {{ t('auth.create_new_account') }}
                    </Link> —
                </p>
            </div>
            
            <form class="mt-8 space-y-6" @submit.prevent="submit">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">{{ t('common.email') }}</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            autocomplete="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            :placeholder="t('common.email')"
                        />
                    </div>
                    <div>
                        <label for="password" class="sr-only">{{ t('common.password') }}</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 focus:z-10 sm:text-sm"
                            :placeholder="t('common.password')"
                        />
                    </div>
                </div>

                <div v-if="form.errors.email" class="text-red-600 text-sm">
                    {{ form.errors.email }}
                </div>

                <div>
                    <button
                        type="submit"
                        :disabled="isDisabled"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50"
                    >
                        {{ t('auth.sign_in') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
