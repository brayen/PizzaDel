import { getDictionaryConfig } from './dictionaryConfig';

export const getFieldLabel = (dictionary, fieldName, t) => {
    const config = getDictionaryConfig(dictionary);
    if (config) {
        return t(`${config.translationContext}.fields.${fieldName}`);
    }
    return t(`dictionaries.fields.${fieldName}`);
};

export const getCurrentDictionaryName = (dictionary, t) => {
    const config = getDictionaryConfig(dictionary);
    if (config) {
        return t(config.translationKey);
    }
    return t('dictionaries.ingredients');
};

export const getDisplayFields = (dictionary, fields) => {
    const config = getDictionaryConfig(dictionary);
    if (config && config.displayOrder) {
        return config.displayOrder.filter(key => fields[key]);
    }
    // Default display order
    const defaultOrder = ['name', 'description', 'is_active'];
    return defaultOrder.filter(key => fields[key]);
};

export const renderField = (fieldName, fieldValue, fieldConfig, relationOptions, t) => {
    if (fieldValue === null || fieldValue === undefined) return '-';

    if (!fieldConfig) return fieldValue;

    if (fieldConfig.type === 'boolean') {
        return fieldValue ? t('common.yes') : t('common.no');
    }

    if (fieldConfig.type === 'number' && fieldName.includes('price')) {
        return `${fieldValue} ${t('common.currency')}`;
    }

    if (fieldConfig.relation && relationOptions[fieldName]) {
        const option = relationOptions[fieldName].find(opt => opt.id === fieldValue);
        return option ? option.name : fieldValue;
    }

    if (fieldName === 'description') {
        return fieldValue ? fieldValue.substring(0, 50) + (fieldValue.length > 50 ? '...' : '') : '-';
    }

    return fieldValue;
};
