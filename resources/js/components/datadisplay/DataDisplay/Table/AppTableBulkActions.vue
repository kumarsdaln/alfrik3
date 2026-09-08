<script setup lang="ts">
import { computed } from 'vue'
import {
    Download,
    Trash,
    X,
} from '@lucide/vue'

import type { RowId } from '@/types/table'

interface Props {
    selectedRows?: RowId[]
}

const props = withDefaults(
    defineProps<Props>(),
    {
        selectedRows: () => [],
    }
)

const emit = defineEmits<{
    (e: 'export', ids: RowId[]): void
    (e: 'delete', ids: RowId[]): void
    (e: 'clear'): void
}>()

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const selectionCount = computed(() => props.selectedRows.length)

const hasSelection = computed(() => selectionCount.value > 0)
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-3 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 -translate-y-3 scale-95"
    >
        <div
            v-if="hasSelection"
            class="relative flex items-center justify-between overflow-hidden
                   border-b border-black/5 bg-white/70 px-5 py-3
                   backdrop-blur-2xl
                   dark:border-white/10 dark:bg-white/[0.03]"
        >
            <!-- Glow -->
            <div
                class="absolute inset-0 pointer-events-none
                       bg-gradient-to-r
                       from-brand/5 via-transparent to-brand/5"
            />

            <!-- Left -->
            <div class="relative z-10 flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center
                           rounded-2xl bg-brand
                           text-sm font-bold text-white
                           shadow-lg shadow-brand/20"
                >
                    {{ selectionCount }}
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ selectionCount }} selected
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Bulk actions available
                    </p>
                </div>

            </div>

            <!-- Right -->
            <div class="relative z-10 flex items-center gap-2">

                <!-- Export -->
                <button
                    type="button"
                    class="group flex h-10 items-center gap-2
                           rounded-2xl border border-black/5
                           bg-white px-4 transition-all duration-200
                           hover:border-brand hover:bg-brand
                           dark:border-white/10
                           dark:bg-white/[0.03]"
                    @click="emit('export', selectedRows)"
                >
                    <Download
                        class="h-4 w-4 text-gray-600 transition
                               group-hover:text-white
                               dark:text-gray-300"
                    />

                    <span
                        class="text-sm font-medium text-gray-700 transition
                               group-hover:text-white
                               dark:text-gray-200"
                    >
                        Export
                    </span>
                </button>

                <!-- Delete -->
                <button
                    type="button"
                    class="group flex h-10 items-center gap-2
                           rounded-2xl border border-red-500/20
                           bg-red-500/10 px-4
                           transition-all duration-200
                           hover:bg-red-500"
                    @click="emit('delete', selectedRows)"
                >
                    <Trash
                        class="h-4 w-4 text-red-500 transition
                               group-hover:text-white"
                    />

                    <span
                        class="text-sm font-medium text-red-500 transition
                               group-hover:text-white"
                    >
                        Delete
                    </span>
                </button>

                <!-- Clear -->
                <button
                    type="button"
                    class="group flex h-10 w-10 items-center justify-center
                           rounded-2xl border border-black/5
                           bg-white transition-all duration-200
                           hover:bg-black
                           dark:border-white/10
                           dark:bg-white/[0.03]
                           dark:hover:bg-white"
                    @click="emit('clear')"
                >
                    <X
                        class="h-4 w-4 text-gray-600 transition
                               group-hover:text-white
                               dark:text-gray-300
                               dark:group-hover:text-black"
                    />
                </button>

            </div>
        </div>
    </Transition>
</template>