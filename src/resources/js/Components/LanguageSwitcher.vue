<script setup>
import { useLocaleStore } from '@/Stores/locale';
import { computed, ref, onMounted, onUnmounted } from 'vue';

const localeStore = useLocaleStore();
const showDropdown = ref(false);
const dropdownRef = ref(null);

const currentLanguage = computed(() => localeStore.currentLanguage);
const supportedLocales = computed(() => localeStore.supportedLocales);

const switchLanguage = async (locale) => {
    showDropdown.value = false;
    await localeStore.setLocale(locale);
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        showDropdown.value = false;
    }
};

const toggleDropdown = (event) => {
    event.stopPropagation();
    showDropdown.value = !showDropdown.value;
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <button
            @click="toggleDropdown"
            class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
        >
            <span class="text-xl">{{ currentLanguage?.flag || '🌐' }}</span>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <!-- Dropdown -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="showDropdown"
                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
            >
                <div class="py-1">
                    <template v-for="lang in supportedLocales" :key="lang.code">
                        <button
                            @click.stop="switchLanguage(lang.code)"
                            :class="[
                                'w-full text-left px-4 py-2 text-sm hover:bg-gray-100 flex items-center space-x-3 transition-colors',
                                lang.code === localeStore.currentLocale ? 'bg-emerald-50 text-emerald-700' : 'text-gray-700'
                            ]"
                        >
                            <span class="text-lg">{{ lang.flag }}</span>
                            <span>{{ lang.name }}</span>
                            <svg
                                v-if="lang.code === localeStore.currentLocale"
                                class="w-4 h-4 text-emerald-600 ml-auto"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </Transition>
    </div>
</template>
