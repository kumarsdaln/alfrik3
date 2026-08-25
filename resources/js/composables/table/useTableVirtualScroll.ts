import {
    computed,
    ref,
    type Ref,
} from 'vue'

export interface UseTableVirtualScrollOptions<T> {
    items: Readonly<Ref<T[]>>
    rowHeight?: number
    containerHeight?: number
    overscan?: number
}

export function useTableVirtualScroll<T>(
    options: UseTableVirtualScrollOptions<T>
) {

    const {
        items,
        rowHeight = 52,
        containerHeight = 600,
        overscan = 5,
    } = options

    const scrollTop = ref(0)

    const visibleCount = computed(() => {

        return Math.ceil(
            containerHeight / rowHeight
        )

    })

    const startIndex = computed(() => {

        return Math.max(
            0,
            Math.floor(
                scrollTop.value / rowHeight
            ) - overscan
        )

    })

    const endIndex = computed(() => {

        return Math.min(
            items.value.length,
            startIndex.value +
            visibleCount.value +
            overscan * 2
        )

    })

    const visibleItems = computed(() => {

        return items.value.slice(
            startIndex.value,
            endIndex.value
        )

    })

    const totalHeight = computed(() => {

        return (
            items.value.length *
            rowHeight
        )

    })

    const offsetTop = computed(() => {

        return (
            startIndex.value *
            rowHeight
        )

    })

    const onScroll = (
        event: Event
    ): void => {

        scrollTop.value = (
            event.target as HTMLElement
        ).scrollTop

    }

    return {
        scrollTop,
        visibleItems,
        totalHeight,
        offsetTop,
        startIndex,
        endIndex,
        onScroll,
    }

}
