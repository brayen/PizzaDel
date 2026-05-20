<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import StaffSidebar from '@/Components/Staff/Sidebar.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { useLocaleStore } from '@/Stores/locale';

const localeStore = useLocaleStore();

const props = defineProps({
    dictionary: {
        type: String,
        default: 'ingredients',
    },
    auth: {
        type: Object,
        required: true
    }
});

const userRole = computed(() => props.auth.user.getRoleNames?.()?.[0] || 'staff');
const userName = computed(() => props.auth.user.name || 'Staff Member');

const dictionaries = ref([]);
const items = ref([]);
const fields = ref({});
const loading = ref(false);
const showModal = ref(false);
const editingItem = ref(null);
const formData = ref({});

const dictionaryNames = {
    ingredients: 'ingredients',
};

const currentDictionaryName = computed(() => {
    return t(`dictionaries.${dictionaryNames[props.dictionary]}`) || props.dictionary;
});

const t = (key, params = {}) => localeStore.t(key, params);

const loadDictionaries = async () => {
    try {
        const response = await fetch('/api/dictionaries');
        const data = await response.json();
        dictionaries.value = data.dictionaries;
    } catch (error) {
        console.error('Failed to load dictionaries:', error);
    }
};

const loadItems = async () => {
    loading.value = true;
    try {
        const response = await fetch(`/api/dictionaries/${props.dictionary}`);
        const data = await response.json();
        items.value = data.items;
        fields.value = data.fields;
    } catch (error) {
        console.error('Failed to load items:', error);
    } finally {
        loading.value = false;
    }
};

const openModal = (item = null) => {
    if (item) {
        editingItem.value = item;
        formData.value = { ...item };
    } else {
        editingItem.value = null;
        formData.value = {};
        Object.keys(fields.value).forEach(key => {
            formData.value[key] = fields.value[key].type === 'boolean' ? true : '';
        });
    }
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingItem.value = null;
};

const saveItem = async () => {
    try {
        const url = editingItem.value
            ? `/api/dictionaries/${props.dictionary}/${editingItem.value.id}`
            : `/api/dictionaries/${props.dictionary}`;

        const method = editingItem.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData.value),
        });

        if (response.ok) {
            await loadItems();
            closeModal();
        } else {
            console.error('Failed to save item');
        }
    } catch (error) {
        console.error('Failed to save item:', error);
    }
};

const deleteItem = async (item) => {
    if (!confirm(t('dictionaries.delete_confirm', { name: item.name }))) {
        return;
    }

    try {
        const response = await fetch(`/api/dictionaries/${props.dictionary}/${item.id}`, {
            method: 'DELETE',
        });

        if (response.ok) {
            await loadItems();
        } else {
            console.error('Failed to delete item');
        }
    } catch (error) {
        console.error('Failed to delete item:', error);
    }
};

const switchDictionary = (dict) => {
    window.location.href = `/staff/dictionaries/${dict}`;
};

const renderField = (fieldName, fieldValue) => {
    if (fieldValue === null || fieldValue === undefined) return '-';

    const fieldConfig = fields.value[fieldName];
    if (!fieldConfig) return fieldValue;

    if (fieldConfig.type === 'boolean') {
        return fieldValue ? t('common.yes') : t('common.no');
    }

    if (fieldConfig.type === 'number' && fieldName.includes('price')) {
        return `${fieldValue} ${t('common.currency')}`;
    }

    if (fieldName === 'slug') {
        return fieldValue;
    }

    if (fieldName === 'description') {
        return fieldValue ? fieldValue.substring(0, 50) + (fieldValue.length > 50 ? '...' : '') : '-';
    }

    return fieldValue;
};

const getDisplayFields = () => {
    const displayOrder = ['name', 'slug', 'description', 'price_per_gram', 'calories_per_gram', 'is_active'];
    return displayOrder.filter(key => fields.value[key]);
};

onMounted(() => {
    localeStore.setContext('staff');
    loadDictionaries();
    loadItems();
});
</script>

<template>
    <Head :title="`${currentDictionaryName} - ${t('dictionaries.title')}`" />

    <div class="flex min-h-screen bg-gray-100">
        <StaffSidebar />

        <div class="flex-1">
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-red-600">📚 {{ t('dictionaries.title') }}</h1>
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
                                {{ t('common.logout') }}
                            </Link>
                        </div>
                    </div>
                </div>
            </header>

            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div class="px-4 py-6 sm:px-0">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">{{ currentDictionaryName }}</h2>
                        <button
                            @click="openModal()"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium"
                        >
                            + {{ t('dictionaries.add') }}
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
                                        v-for="field in getDisplayFields()"
                                        :key="field"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ t('dictionaries.fields.' + field) }}
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ t('common.edit') }}/{{ t('common.delete') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in items" :key="item.id">
                                    <td
                                        v-for="field in getDisplayFields()"
                                        :key="field"
                                        class="px-6 py-4 whitespace-nowrap"
                                    >
                                        <span class="text-sm text-gray-900">{{ renderField(field, item[field]) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button
                                            @click="openModal(item)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-4"
                                        >
                                            {{ t('dictionaries.edit') }}
                                        </button>
                                        <button
                                            @click="deleteItem(item)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            {{ t('dictionaries.delete') }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="items.length === 0">
                                    <td :colspan="getDisplayFields().length + 1" class="px-6 py-4 text-center text-gray-500">
                                        {{ t('dictionaries.no_records') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
            <div class="relative p-5 border w-full max-w-lg shadow-lg rounded-md bg-white mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    {{ editingItem ? t('dictionaries.edit_item') : t('dictionaries.add_item') }}
                </h3>

                <form @submit.prevent="saveItem">
                    <div
                        v-for="(fieldConfig, fieldName) in fields"
                        :key="fieldName"
                        class="mb-4"
                    >
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            {{ t('dictionaries.fields.' + fieldName) }}
                            <span v-if="fieldConfig.required" class="text-red-500">*</span>
                        </label>

                        <input
                            v-if="fieldConfig.type === 'text'"
                            v-model="formData[fieldName]"
                            type="text"
                            :required="fieldConfig.required"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        />

                        <textarea
                            v-else-if="fieldConfig.type === 'textarea'"
                            v-model="formData[fieldName]"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        ></textarea>

                        <input
                            v-else-if="fieldConfig.type === 'number'"
                            v-model.number="formData[fieldName]"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                        />

                        <label v-else-if="fieldConfig.type === 'boolean'" class="flex items-center">
                            <input
                                v-model="formData[fieldName]"
                                type="checkbox"
                                class="mr-2"
                            />
                            <span class="text-gray-700">{{ fieldConfig.label }}</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
                        >
                            {{ t('dictionaries.cancel') }}
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                        >
                            {{ t('dictionaries.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
