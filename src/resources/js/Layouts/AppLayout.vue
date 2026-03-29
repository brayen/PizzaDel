<script setup>
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Header -->
        <Header />

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <Footer />
    </div>
</template>
