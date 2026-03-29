<script setup>
import { Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <main class="flex-1">
            <slot />
        </main>

        <Footer />
    </div>
</template>
