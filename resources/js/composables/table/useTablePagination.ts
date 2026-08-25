import { computed, type ComputedRef } from 'vue'

interface PaginationOptions {
    currentPage: ComputedRef<number>
    lastPage: ComputedRef<number>
    prevPageUrl?: ComputedRef<string | null | undefined>
    nextPageUrl?: ComputedRef<string | null | undefined>
    filters?: ComputedRef<Record<string, unknown>>
}

export function useTablePagination({
    currentPage,
    lastPage,
    prevPageUrl,
    nextPageUrl,
    filters,
}: PaginationOptions) {

    const hasPrevious = computed(() =>
        prevPageUrl
            ? !!prevPageUrl.value
            : currentPage.value > 1
    )

    const hasNext = computed(() =>
        nextPageUrl
            ? !!nextPageUrl.value
            : currentPage.value < lastPage.value
    )
    const pages = computed(() => {
        const items: number[] = []
        for (let page = 1; page <= lastPage.value; page++) {
            items.push(page)
        }
        return items
    })

    const generateUrl = (
        url?: string | null
    ): string => {

        if (!url) {
            return '#'
        }

        const params = new URLSearchParams()
        Object.entries(filters?.value ?? {}).forEach(([key, value]) => {
            if (
                value === '' ||
                value === null ||
                value === undefined
            ) {
                return
            }

            if (Array.isArray(value)) {
                value.forEach(item =>
                    params.append(key, String(item))
                )
                return
            }

            params.append(key, String(value))
        })

        const query = params.toString()

        if (!query) {
            return url
        }

        return `${url}${url.includes('?') ? '&' : '?'}${query}`
    }

    return {
        pages,
        hasPrevious,
        hasNext,
        generateUrl,
    }
}
