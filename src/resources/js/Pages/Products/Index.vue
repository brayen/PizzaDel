<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted, computed } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();

// Initialize locale store
onMounted(() => {
    localeStore.initialize();
});

// Get translations
const t = (key, params = {}) => localeStore.t(key, params);

// Reactive data
const products = ref([]);
const loading = ref(false);
const hasMore = ref(true);
const offset = ref(0);
const limit = ref(20);

// Load products
const loadProducts = async (append = false) => {
    loading.value = true;
    
    try {
        const response = await fetch(`/api/products?limit=${limit.value}&offset=${offset.value}&locale=${localeStore.currentLocale}`);
        const data = await response.json();
        
        if (append) {
            products.value = [...products.value, ...data.products];
        } else {
            products.value = data.products;
        }
        
        hasMore.value = data.has_more;
        offset.value += data.products.length;
    } catch (error) {
        console.error('Failed to load products:', error);
    } finally {
        loading.value = false;
    }
};

// Load more products
const loadMore = () => {
    if (!loading.value && hasMore.value) {
        loadProducts(true);
    }
};

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);
const showSearch = ref(false);

const searchProducts = async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        showSearch.value = false;
        return;
    }
    
    try {
        const response = await fetch(`/api/search?q=${encodeURIComponent(searchQuery.value)}&locale=${localeStore.currentLocale}`);
        const data = await response.json();
        searchResults.value = data.products;
        showSearch.value = true;
    } catch (error) {
        console.error('Failed to search products:', error);
    }
};

// Watch search query
import { watch } from 'vue';
watch(searchQuery, searchProducts);

// Initial load
onMounted(() => {
    loadProducts();
    
    // Listen for locale changes
    window.addEventListener('locale-changed', (event) => {
        // Reset pagination when locale changes
        offset.value = 0;
        hasMore.value = true;
        loadProducts(); // Reload products with new locale
    });
});
</script>

<template>
    <AppLayout>
        <Head :title="t('common.products')" />

        <div class="min-h-screen bg-gray-100">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ t('common.products') }}
                    </h1>
                    
                    <!-- Search -->
                    <div class="relative">
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="t('common.search_products')"
                            class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        />
                        
                        <!-- Search Results Dropdown -->
                        <div
                            v-if="showSearch && searchResults.length > 0"
                            class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                        >
                            <div class="max-h-64 overflow-y-auto">
                                <a
                                    v-for="product in searchResults"
                                    :key="product.id"
                                    :href="`/product/${product.slug}`"
                                    class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0"
                                >
                                    <div class="font-medium text-gray-900">{{ product.name }}</div>
                                    <div class="text-sm text-gray-600">{{ product.price }}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="product in products"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300"
                >
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {{ product.name }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ product.description }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-emerald-600">
                                {{ product.price }}
                            </span>
                            <a
                                :href="`/product/${product.slug}`"
                                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                {{ t('common.view_details') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600"></div>
            </div>

            <!-- Load More -->
            <div v-if="hasMore && !loading" class="text-center py-8">
                <button
                    @click="loadMore"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-md font-medium transition-colors"
                >
                    {{ t('common.load_more') }}
                </button>
            </div>

            <!-- No Results -->
            <div v-if="!loading && products.length === 0" class="text-center py-12">
                <div class="text-gray-500 text-lg">
                    {{ t('common.no_products_found') }}
                </div>
            </div>
        </div>
    </div>
    </AppLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
