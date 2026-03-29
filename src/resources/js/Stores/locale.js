import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

export const useLocaleStore = defineStore('locale', {
    state: () => ({
        currentLocale: 'en',
        translations: {},
        supportedLocales: [
            { code: 'en', name: 'English', flag: '🇺🇸' },
            { code: 'ua', name: 'Українська', flag: '🇺🇦' },
            { code: 'de', name: 'Deutsch', flag: '🇩🇪' },
        ],
    }),

    getters: {
        currentLanguage: (state) => {
            return state.supportedLocales.find(lang => lang.code === state.currentLocale)
        },
        t: (state) => {
            return (key, params = {}) => {
                const keys = key.split('.')
                let value = state.translations
                
                for (const k of keys) {
                    value = value?.[k]
                }
                
                if (!value) return key
                
                // Replace parameters like :year, :name etc.
                return Object.keys(params).reduce((str, param) => {
                    return str.replace(`:${param}`, params[param])
                }, value)
            }
        }
    },

    actions: {
        async setLocale(locale) {
            if (!this.supportedLocales.find(lang => lang.code === locale)) {
                return false
            }

            try {
                // Store in LocalStorage first
                localStorage.setItem('locale', locale)
                
                // Also store in session for backend
                await router.post('/locale', { locale })
                
                // Update store state
                this.currentLocale = locale
                await this.loadTranslations()
                
                // Update HTML lang attribute
                document.documentElement.lang = locale
                
                // Trigger custom event for components to reload data
                window.dispatchEvent(new CustomEvent('locale-changed', { detail: { locale } }));
                
                return true
            } catch (error) {
                console.error('Failed to switch locale:', error)
                return false
            }
        },

        async loadTranslations() {
            try {
                const response = await fetch(`/translations/${this.currentLocale}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                this.translations = await response.json();
            } catch (error) {
                console.error('Failed to load translations:', error);
                this.translations = {}
            }
        },

        initialize() {
            const page = usePage()
            const props = page.props
            
            // Get locale from LocalStorage first, then from props
            const savedLocale = localStorage.getItem('locale')
            this.currentLocale = savedLocale || props.locale || 'en'
            
            // Load translations for current locale
            this.loadTranslations()
            
            // Update HTML lang attribute
            document.documentElement.lang = this.currentLocale
        }
    }
})
