import { ref, computed, onMounted } from 'vue';
import { useLocaleStore } from '@/Stores/locale';

export const useStorage = (props) => {
    const localeStore = useLocaleStore();

    const t = (key, params = {}) => localeStore.t(key, params);

    const userRole = computed(() => props.auth.user.getRoleNames?.()?.[0] || 'staff');
    const userName = computed(() => props.auth.user.name || 'Staff Member');

    const activeTab = ref('stock');
    const ingredients = ref([]);
    const movements = ref([]);
    const loading = ref(false);

    // Form data for inbound/outbound
    const formData = ref({
        ingredient_id: '',
        quantity: '',
        date: new Date().toISOString().split('T')[0],
        notes: '',
        reference: '',
    });

    const showAddModal = ref(false);
    const modalType = ref('inbound');

    const loadIngredients = async () => {
        try {
            loading.value = true;
            const response = await fetch('/api/storage');
            const data = await response.json();
            ingredients.value = data.ingredients;
        } catch (error) {
            console.error('Failed to load ingredients:', error);
        } finally {
            loading.value = false;
        }
    };

    const loadMovements = async (type = null, ingredientId = null) => {
        try {
            loading.value = true;
            let url = '/api/storage/movements';
            const params = new URLSearchParams();
            if (type) params.append('type', type);
            if (ingredientId) params.append('ingredient_id', ingredientId);
            if (params.toString()) url += '?' + params.toString();

            const response = await fetch(url);
            const data = await response.json();
            movements.value = data.data;
        } catch (error) {
            console.error('Failed to load movements:', error);
        } finally {
            loading.value = false;
        }
    };

    const openAddModal = (type) => {
        modalType.value = type;
        showAddModal.value = true;
        formData.value = {
            ingredient_id: '',
            quantity: '',
            date: new Date().toISOString().split('T')[0],
            notes: '',
            reference: '',
        };
    };

    const closeModal = () => {
        showAddModal.value = false;
        formData.value = {
            ingredient_id: '',
            quantity: '',
            date: new Date().toISOString().split('T')[0],
            notes: '',
            reference: '',
        };
    };

    const saveMovement = async () => {
        try {
            const url = modalType.value === 'inbound' ? '/api/storage/inbound' : '/api/storage/outbound';
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData.value),
            });

            if (response.ok) {
                await loadIngredients();
                if (activeTab.value === 'inbound' || activeTab.value === 'outbound') {
                    await loadMovements(activeTab.value);
                }
                closeModal();
            } else {
                const error = await response.json();
                alert(error.error || 'Failed to save movement');
            }
        } catch (error) {
            console.error('Failed to save movement:', error);
        }
    };

    const switchTab = (tab) => {
        activeTab.value = tab;
        if (tab === 'inbound') {
            loadMovements('inbound');
        } else if (tab === 'outbound') {
            loadMovements('outbound');
        }
    };

    const initialize = async () => {
        await localeStore.initialize();
        await localeStore.setContext('staff');
        loadIngredients();
    };

    return {
        userRole,
        userName,
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
    };
};
