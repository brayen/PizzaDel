export const loadDictionaries = async () => {
    try {
        const response = await fetch('/api/dictionaries');
        const data = await response.json();
        return data.dictionaries;
    } catch (error) {
        console.error('Failed to load dictionaries:', error);
        return [];
    }
};

export const loadItems = async (dictionary) => {
    try {
        const response = await fetch(`/api/dictionaries/${dictionary}`);
        const data = await response.json();
        return {
            items: data.items,
            fields: data.fields,
        };
    } catch (error) {
        console.error('Failed to load items:', error);
        return {
            items: [],
            fields: {},
        };
    }
};

export const loadRelationOptions = async (fieldName, fieldConfig) => {
    try {
        const response = await fetch(`/api/dictionaries/${fieldConfig.relation}`);
        const data = await response.json();
        let options = data.items;

        // Apply filter if specified
        if (fieldConfig.filter) {
            options = options.filter(item => {
                for (const [key, value] of Object.entries(fieldConfig.filter)) {
                    if (item[key] !== value) return false;
                }
                return true;
            });
        }

        return options.map(item => ({
            id: item.id,
            name: item.name,
        }));
    } catch (error) {
        console.error(`Failed to load relation options for ${fieldName}:`, error);
        return [];
    }
};

export const saveItem = async (dictionary, item, formData) => {
    try {
        const url = item
            ? `/api/dictionaries/${dictionary}/${item.id}`
            : `/api/dictionaries/${dictionary}`;

        const method = item ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        });

        return response.ok;
    } catch (error) {
        console.error('Failed to save item:', error);
        return false;
    }
};

export const deleteItem = async (dictionary, itemId) => {
    try {
        const response = await fetch(`/api/dictionaries/${dictionary}/${itemId}`, {
            method: 'DELETE',
        });

        return response.ok;
    } catch (error) {
        console.error('Failed to delete item:', error);
        return false;
    }
};
