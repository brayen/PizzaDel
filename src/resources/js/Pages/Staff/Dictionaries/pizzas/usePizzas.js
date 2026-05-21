import { ref, computed } from 'vue';
import { useLocaleStore } from '@/Stores/locale';
import { loadDictionaries, loadItems, loadRelationOptions, saveItem, deleteItem } from '@/utils/dictionaryApi';
import { getCurrentDictionaryName, getDisplayFields, renderField } from '@/utils/dictionaryHelpers';

export const usePizzas = (props) => {
    const localeStore = useLocaleStore();

    const t = (key, params = {}) => localeStore.t(key, params);

    const dictionaries = ref([]);
    const items = ref([]);
    const fields = ref({});
    const loading = ref(false);
    const showModal = ref(false);
    const editingItem = ref(null);
    const formData = ref({});
    const relationOptions = ref({});

    const currentDictionaryName = computed(() => {
        return getCurrentDictionaryName('pizzas', t);
    });

    const loadDictionariesData = async () => {
        dictionaries.value = await loadDictionaries();
    };

    const loadItemsData = async () => {
        loading.value = true;
        try {
            const data = await loadItems('pizzas');
            items.value = data.items;
            fields.value = data.fields;

            // Load relation options for fields that have relation config
            for (const [fieldName, fieldConfig] of Object.entries(data.fields)) {
                if (fieldConfig.relation) {
                    relationOptions.value[fieldName] = await loadRelationOptions(fieldName, fieldConfig);
                }
            }
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

    const saveItemData = async () => {
        const success = await saveItem('pizzas', editingItem.value, formData.value);
        if (success) {
            await loadItemsData();
            closeModal();
        }
    };

    const deleteItemData = async (item) => {
        if (!confirm(t('pizzas.delete_confirm', { name: item.name }))) {
            return;
        }

        const success = await deleteItem('pizzas', item.id);
        if (success) {
            await loadItemsData();
        }
    };

    const renderFieldData = (fieldName, fieldValue) => {
        const fieldConfig = fields.value[fieldName];
        return renderField(fieldName, fieldValue, fieldConfig, relationOptions.value, t);
    };

    const getDisplayFieldsData = () => {
        return getDisplayFields('pizzas', fields.value);
    };

    const getFieldLabel = (fieldName) => {
        return t(`pizzas.fields.${fieldName}`);
    };

    return {
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
    };
};
