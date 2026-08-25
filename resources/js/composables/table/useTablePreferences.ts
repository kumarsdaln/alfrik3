import { computed, ref, watch } from 'vue'

interface TablePreferences {
    hiddenColumns: string[]
    pageSize: number
    density: 'comfortable' | 'compact'
    sortKey: string
    sortDirection: 'asc' | 'desc'
}

const DEFAULT_PREFERENCES: TablePreferences = {
    hiddenColumns: [],
    pageSize: 10,
    density: 'comfortable',
    sortKey: '',
    sortDirection: 'asc',
}

export function useTablePreferences(
    storageKey: string
) {

    const preferences = ref<TablePreferences>(
        loadPreferences()
    )

    function loadPreferences(): TablePreferences {

        const raw = localStorage.getItem(storageKey)

        if (!raw) {
            return { ...DEFAULT_PREFERENCES }
        }

        try {

            return {
                ...DEFAULT_PREFERENCES,
                ...JSON.parse(raw),
            }

        } catch {

            return { ...DEFAULT_PREFERENCES }

        }

    }

    watch(
        preferences,
        value => {

            localStorage.setItem(
                storageKey,
                JSON.stringify(value)
            )

        },
        {
            deep: true,
        }
    )

    const hiddenColumns = computed({
        get: () => preferences.value.hiddenColumns,
        set: value => {
            preferences.value.hiddenColumns = value
        },
    })

    const pageSize = computed({
        get: () => preferences.value.pageSize,
        set: value => {
            preferences.value.pageSize = value
        },
    })

    const density = computed({
        get: () => preferences.value.density,
        set: value => {
            preferences.value.density = value
        },
    })

    const sortKey = computed({
        get: () => preferences.value.sortKey,
        set: value => {
            preferences.value.sortKey = value
        },
    })

    const sortDirection = computed({
        get: () => preferences.value.sortDirection,
        set: value => {
            preferences.value.sortDirection = value
        },
    })

    const reset = () => {

        preferences.value = {
            ...DEFAULT_PREFERENCES,
        }

    }

    return {
        preferences,
        hiddenColumns,
        pageSize,
        density,
        sortKey,
        sortDirection,
        reset,
    }
}