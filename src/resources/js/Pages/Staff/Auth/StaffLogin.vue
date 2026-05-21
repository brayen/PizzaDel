<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();
const t = (key) => localeStore.t(key);

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/staff/login', {
        onFinish: () => form.reset('password'),
    });
};

const isDisabled = computed(() => form.processing);

onMounted(async () => {
    await localeStore.initialize();
    await localeStore.setContext('staff');
});
</script>

<template>
    <Head :title="t('auth.staff_login')" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full space-y-8">
            <div>
                <Link href="/" class="flex justify-center">
                    <h1 class="text-3xl font-bold text-emerald-600">🍕 PizzaDel</h1>
                </Link>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    {{ t('messages.staff_portal') }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    {{ t('auth.sign_in_to_staff_account') }}
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

                <div class="text-center">
                    <Link 
                        href="/login" 
                        class="font-medium text-emerald-600 hover:text-emerald-500 text-sm"
                    >
                        {{ t('auth.customer_login') }}
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
