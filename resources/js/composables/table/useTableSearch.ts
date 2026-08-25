import { computed, ref, watch } from 'vue'

export interface UseTableSearchOptions {
    initialValue?: string
    debounce?: number
    trim?: boolean
    immediate?: boolean
    onSearch?: (value: string) => void
}

export function useTableSearch(
    options: UseTableSearchOptions = {}
) {

    const {
        initialValue = '',
        debounce = 500,
        trim = true,
        immediate = true,
        onSearch,
    } = options

    const search = ref(initialValue)

    const searching = ref(false)

    let timer: ReturnType<typeof setTimeout> | null = null

    const value = computed(() => {

        return trim
            ? search.value.trim()
            : search.value

    })

    const apply = (): void => {

        searching.value = true

        onSearch?.(value.value)

        searching.value = false

    }

    const clear = (): void => {

        search.value = ''

        apply()

    }

    watch(
        value,
        () => {

            if (!immediate) {
                return
            }

            if (timer) {
                clearTimeout(timer)
            }

            timer = setTimeout(() => {

                apply()

            }, debounce)

        }
    )

    return {
        search,
        value,
        searching,
        apply,
        clear,
    }

}