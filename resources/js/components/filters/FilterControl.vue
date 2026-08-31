<script setup lang="ts">
import { computed, ref } from 'vue'

import AppInput from '@/components/form/AppInput.vue'

import { Button } from '@/components/ui/button'
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet'

interface Props {
    search?: string
    searchPlaceholder?: string
    filterCount?: number
}

const props = withDefaults(
    defineProps<Props>(),
    {
        search: '',
        searchPlaceholder: 'Search...',
        filterCount: 0,
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
    set: value => emit('update:search', String(value)),
})

function clearFilters() {
    emit('clear')
}

function applyFilters() {
    emit('apply')
    open.value = false
}
</script>

<template>
    <div class="flex items-end gap-3">

        <!-- Search -->

        <div class="min-w-0 flex-1">
            <AppInput
                v-model="search"
                name="search"
                :placeholder="searchPlaceholder"
            />
        </div>


        <!-- Filter Button -->

        <Button
            type="button"
            variant="outline"
            class="h-10 shrink-0 gap-2"
            @click="open = true"
        >
            <svg
                class="size-4"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M4 6h16" />
                <path d="M7 12h10" />
                <path d="M10 18h4" />
            </svg>

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
                    text-xs
                    font-medium
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
                class="flex w-full flex-col sm:max-w-md"
            >
                <SheetHeader>
                    <SheetTitle>
                        Filters
                    </SheetTitle>
                </SheetHeader>


                <!-- Filter Fields -->

                <div class="min-h-0 flex-1 overflow-y-auto px-1 py-6">
                    <slot />
                </div>


                <!-- Actions -->

                <div
                    class="
                        flex
                        shrink-0
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
                </div>
            </SheetContent>
        </Sheet>

    </div>
</template>