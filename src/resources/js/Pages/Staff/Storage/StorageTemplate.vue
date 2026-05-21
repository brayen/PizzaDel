<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import StaffLayout from '@/Components/Staff/Layout.vue';
import { useStorage } from './useStorage';

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const {
    activeTab,
    ingredients,
    movements,
    loading,
    formData,
    showAddModal,
    modalType,
    loadIngredients,
    loadMovements,
    openAddModal,
    closeModal,
    saveMovement,
    switchTab,
    initialize,
    t,
} = useStorage(props);

onMounted(async () => {
    await initialize();
});
</script>

<template>
    <Head :title="`${t('storage.title')}`" />
    
    <StaffLayout :title="t('storage.title')" icon="📦" :user="auth.user">
        <h1 class="text-2xl font-bold mb-6">{{ t('storage.title') }}</h1>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-6">
            <button
                @click="switchTab('stock')"
                :class="[
                    'px-4 py-2 font-medium',
                    activeTab === 'stock' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'
                ]"
            >
                {{ t('storage.tabs.stock') }}
            </button>
            <button
                @click="switchTab('inbound')"
                :class="[
                    'px-4 py-2 font-medium',
                    activeTab === 'inbound' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'
                ]"
            >
                {{ t('storage.tabs.inbound') }}
            </button>
            <button
                @click="switchTab('outbound')"
                :class="[
                    'px-4 py-2 font-medium',
                    activeTab === 'outbound' ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-gray-700'
                ]"
            >
                {{ t('storage.tabs.outbound') }}
            </button>
        </div>

        <!-- Stock Tab -->
        <div v-if="activeTab === 'stock'">
            <div v-if="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            </div>
            <div v-else class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.ingredient') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.category') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.current_stock') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="ingredient in ingredients" :key="ingredient.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ ingredient.name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ ingredient.category }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm text-gray-900">{{ ingredient.current_stock }}</span>
                            </td>
                        </tr>
                        <tr v-if="ingredients.length === 0">
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                {{ t('storage.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inbound Tab -->
        <div v-if="activeTab === 'inbound'">
            <div class="mb-4">
                <button
                    @click="openAddModal('inbound')"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                >
                    {{ t('storage.add_inbound') }}
                </button>
            </div>
            <div v-if="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            </div>
            <div v-else class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.ingredient') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.quantity') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.reference') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.notes') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="movement in movements" :key="movement.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.date }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.ingredient?.name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm text-gray-900">+{{ movement.quantity }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.reference }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.notes }}</span>
                            </td>
                        </tr>
                        <tr v-if="movements.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                {{ t('storage.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Outbound Tab -->
        <div v-if="activeTab === 'outbound'">
            <div class="mb-4">
                <button
                    @click="openAddModal('outbound')"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                >
                    {{ t('storage.add_outbound') }}
                </button>
            </div>
            <div v-if="loading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
            </div>
            <div v-else class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.ingredient') }}
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.quantity') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.reference') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ t('storage.notes') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="movement in movements" :key="movement.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.date }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.ingredient?.name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm text-gray-900">-{{ movement.quantity }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.reference }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">{{ movement.notes }}</span>
                            </td>
                        </tr>
                        <tr v-if="movements.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                {{ t('storage.no_records') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Movement Modal -->
        <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">
                    {{ modalType === 'inbound' ? t('storage.add_inbound') : t('storage.add_outbound') }}
                </h3>
                <form @submit.prevent="saveMovement">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('storage.ingredient') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="formData.ingredient_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            <option value="">Select...</option>
                            <option v-for="ingredient in ingredients" :key="ingredient.id" :value="ingredient.id">
                                {{ ingredient.name }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('storage.quantity') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model.number="formData.quantity"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('storage.date') }}
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="formData.date"
                            type="date"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('storage.reference') }}
                        </label>
                        <input
                            v-model="formData.reference"
                            type="text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('storage.notes') }}
                        </label>
                        <textarea
                            v-model="formData.notes"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 mr-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            {{ t('storage.cancel') }}
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            {{ t('storage.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </StaffLayout>
</template>
