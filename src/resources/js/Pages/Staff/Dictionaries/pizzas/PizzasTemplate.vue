<script setup>
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import StaffLayout from '@/Components/Staff/Layout.vue';
import { useLocaleStore } from '@/Stores/locale';
import { usePizzas } from './usePizzas';

const props = defineProps({
    auth: {
        type: Object,
        required: true
    }
});

const localeStore = useLocaleStore();

const {
    dictionaries,
    items,
    fields,
    loading,
    showModal,
    editingItem,
    formData,
    relationOptions,
    currentDictionaryName,
    loadDictionariesData,
    loadItemsData,
    openModal,
    closeModal,
    saveItemData,
    deleteItemData,
    renderFieldData,
    getDisplayFieldsData,
    getFieldLabel,
    t,
} = usePizzas(props);

onMounted(async () => {
    await localeStore.initialize();
    await localeStore.setContext('staff');
    loadDictionariesData();
    loadItemsData();
});
</script>

<template>
    <Head :title="`${currentDictionaryName} - ${t('dictionaries.title')}`" />
    
    <StaffLayout :title="t('dictionaries.title')" icon="📚" :user="auth.user">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">{{ currentDictionaryName }}</h2>
            <button
                @click="openModal()"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium"
            >
                + {{ t('pizzas.add') }}
            </button>
        </div>

        <div v-if="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-red-600"></div>
        </div>

        <div v-else class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            v-for="field in getDisplayFieldsData()"
                            :key="field"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                            {{ getFieldLabel(field) }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ t('common.edit') }}/{{ t('common.delete') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in items" :key="item.id">
                        <td
                            v-for="field in getDisplayFieldsData()"
                            :key="field"
                            class="px-6 py-4 whitespace-nowrap"
                        >
                            <span class="text-sm text-gray-900">{{ renderFieldData(field, item[field]) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                                @click="openModal(item)"
                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                            >
                                {{ t('pizzas.edit') }}
                            </button>
                            <button
                                @click="deleteItemData(item)"
                                class="text-red-600 hover:text-red-900"
                            >
                                {{ t('pizzas.delete') }}
                            </button>
                        </td>
                    </tr>
                    <tr v-if="items.length === 0">
                        <td :colspan="getDisplayFieldsData().length + 1" class="px-6 py-4 text-center text-gray-500">
                            {{ t('pizzas.no_records') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
            <div class="relative p-5 border w-full max-w-lg shadow-lg rounded-md bg-white mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    {{ editingItem ? t('pizzas.edit_item') : t('pizzas.add_item') }}
                </h3>

                <form @submit.prevent="saveItemData">
                    <div
                        v-for="(fieldConfig, fieldName) in fields"
                        :key="fieldName"
                        class="mb-4"
                    >
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ getFieldLabel(fieldName) }}
                            <span v-if="fieldConfig.required" class="text-red-500">*</span>
                        </label>

                        <input
                            v-if="fieldConfig.type === 'text'"
                            v-model="formData[fieldName]"
                            type="text"
                            :required="fieldConfig.required"
                            :disabled="fieldConfig.readonly"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                        />

                        <select
                            v-else-if="fieldConfig.type === 'select'"
                            v-model="formData[fieldName]"
                            :required="fieldConfig.required"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        >
                            <option value="">Select...</option>
                            <option
                                v-if="fieldConfig.relation"
                                v-for="option in relationOptions[fieldName] || []"
                                :key="option.id"
                                :value="option.id"
                            >
                                {{ option.name }}
                            </option>
                            <option
                                v-else
                                v-for="(label, value) in fieldConfig.options"
                                :key="value"
                                :value="value"
                            >
                                {{ label }}
                            </option>
                        </select>

                        <textarea
                            v-else-if="fieldConfig.type === 'textarea'"
                            v-model="formData[fieldName]"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                        ></textarea>

                        <input
                            v-else-if="fieldConfig.type === 'number'"
                            v-model.number="formData[fieldName]"
                            type="number"
                            step="0.01"
                            min="0"
                            :disabled="fieldConfig.readonly"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                        />

                        <label v-else-if="fieldConfig.type === 'boolean'" class="flex items-center">
                            <input
                                v-model="formData[fieldName]"
                                type="checkbox"
                                class="mr-2 disabled:cursor-not-allowed"
                            />
                            <span class="text-gray-700">{{ getFieldLabel(fieldName) }}</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            {{ t('pizzas.cancel') }}
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
                        >
                            {{ t('pizzas.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </StaffLayout>
</template>
