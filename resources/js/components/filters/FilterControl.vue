<script setup lang="ts">
import { computed, ref } from 'vue'
import { Search, SlidersHorizontal, X } from '@lucide/vue'

import AppInput from '@/components/form/AppInput.vue'

import { Button } from '@/components/ui/button'
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet'

interface Props {
    search?: string
    searchPlaceholder?: string
    filterCount?: number
    title?: string
    description?: string
    showSearch?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        search: '',
        searchPlaceholder: 'Search...',
        filterCount: 0,
        title: 'Filters',
        description: 'Refine the results using the filters below.',
        showSearch: true,
    },
)

const emit = defineEmits<{
    'update:search': [value: string]
    clear: []
    apply: []
}>()

const open = ref(false)

const search = computed({
    get: () => props.search,
    set: (value: string | number) => {
        emit('update:search', String(value))
    },
})

const hasSearch = computed(() => Boolean(props.search.trim()))

const hasActiveFilters = computed(() =>
    props.filterCount > 0 || hasSearch.value,
)

function clearSearch() {
    search.value = ''
}

function clearFilters() {
    emit('clear')
}

function applyFilters() {
    emit('apply')
    open.value = false
}
</script>

<template>
    <div class="flex w-full items-center gap-3">
        <!-- Search -->
        <div
            v-if="showSearch"
            class="relative min-w-0 flex-1"
        >
            <Search
                class="
                    pointer-events-none
                    absolute
                    left-3
                    top-1/2
                    z-10
                    size-4
                    -translate-y-1/2
                    text-muted-foreground
                "
            />

            <AppInput
                v-model="search"
                name="search"
                :placeholder="searchPlaceholder"
                class="pl-10 pr-10"
            />

            <button
                v-if="hasSearch"
                type="button"
                aria-label="Clear search"
                class="
                    absolute
                    right-3
                    top-1/2
                    z-10
                    -translate-y-1/2
                    text-muted-foreground
                    transition-colors
                    hover:text-foreground
                "
                @click="clearSearch"
            >
                <X class="size-4" />
            </button>
        </div>

        <!-- Filter Button -->
        <Button
            type="button"
            variant="outline"
            class="h-10 shrink-0 gap-2"
            :aria-expanded="open"
            aria-haspopup="dialog"
            @click="open = true"
        >
            <SlidersHorizontal class="size-4" />

            <span>Filters</span>

            <span
                v-if="filterCount > 0"
                class="
                    inline-flex
                    min-w-5
                    items-center
                    justify-center
                    rounded-full
                    bg-foreground
                    px-1.5
                    py-0.5
                    text-xs
                    font-medium
                    leading-none
                    text-background
                "
            >
                {{ filterCount }}
            </span>
        </Button>

        <!-- Filter Sheet -->
        <Sheet v-model:open="open">
            <SheetContent
                side="right"
                class="flex h-full w-full flex-col sm:max-w-md"
            >
                <SheetHeader class="border-b border-border pb-5 text-left">
                    <div class="flex items-center justify-between">
                        <SheetTitle class="text-lg">
                            {{ title }}
                        </SheetTitle>

                        <span
                            v-if="filterCount > 0"
                            class="text-xs text-muted-foreground"
                        >
                            {{ filterCount }} active
                        </span>
                    </div>

                    <SheetDescription>
                        {{ description }}
                    </SheetDescription>
                </SheetHeader>

                <!-- Filter Fields -->
                <div class="min-h-0 flex-1 overflow-y-auto px-1 py-6">
                    <slot />
                </div>

                <!-- Footer Actions -->
                <SheetFooter
                    class="
                        flex
                        flex-row
                        items-center
                        justify-between
                        gap-3
                        border-t
                        border-border
                        pt-4
                    "
                >
                    <Button
                        type="button"
                        variant="ghost"
                        :disabled="!hasActiveFilters"
                        @click="clearFilters"
                    >
                        Clear all
                    </Button>

                    <Button
                        type="button"
                        @click="applyFilters"
                    >
                        Apply filters
                    </Button>
                </SheetFooter>
            </SheetContent>
        </Sheet>
    </div>
</template>
