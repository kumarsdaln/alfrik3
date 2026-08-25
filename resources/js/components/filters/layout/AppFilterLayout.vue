<script setup lang="ts">
import { computed, ref, useSlots } from 'vue'
import AppModal from '@/components/ui/AppModal.vue'
import { Funnel } from '@lucide/vue'

interface Props {
    filters?: Record<string, unknown>
    mobileTitle?: string
}

const props = withDefaults(defineProps<Props>(), {
    filters: () => ({}),
    mobileTitle: 'Filters',
})

const emit = defineEmits<{
    reset: []
    apply: []
}>()

const showFilters = ref(false)

const slots = useSlots()

const hasFilterSlots = computed(() => {
    return Boolean(
        slots['inline-filters'] ||
        slots['modal-filters']
    )
})

const activeFilterCount = computed(() => {
    return Object.values(props.filters).filter((value) => {
        if (value === null || value === undefined) {
            return false
        }

        if (typeof value === 'string' && value.trim() === '') {
            return false
        }

        if (Array.isArray(value) && value.length === 0) {
            return false
        }

        return true
    }).length
})

const openFilters = () => {
    showFilters.value = true
}

const closeFilters = () => {
    showFilters.value = false
}

const applyFilters = () => {
    emit('apply')
    closeFilters()
}

const resetFilters = () => {
    emit('reset')
    closeFilters()
}
</script>

<template>
    <!-- TOP BAR -->
    <div class="relative flex items-center gap-3">
        <!-- SEARCH -->
        <div class="flex-1">
            <slot name="search" />
        </div>

        <!-- INLINE FILTERS -->
        <div class="hidden items-center gap-3 lg:flex">
            <slot name="inline-filters" />
        </div>

        <!-- FILTER BUTTON -->
        <button
            v-if="hasFilterSlots"
            type="button"
            class="
                relative shrink-0
                flex items-center gap-3
                h-11 px-4
                rounded-2xl
                border border-black/5
                dark:border-white/10
                bg-white
                dark:bg-white/[0.03]
                hover:bg-gray-50
                dark:hover:bg-white/[0.05]
                transition-all duration-200
                focus:outline-none
                focus:ring-2
                focus:ring-brand/30
            "
            :aria-expanded="showFilters"
            aria-haspopup="dialog"
            @click="openFilters"
        >
            <Funnel
                class="h-4 w-4 text-gray-500"
                aria-hidden="true"
            />

            <!-- LABEL -->
            <span
                class="
                    hidden sm:block
                    text-sm font-medium
                    text-gray-700
                    dark:text-gray-200
                "
            >
                Filters
            </span>

            <!-- ACTIVE FILTER COUNT -->
            <span
                v-if="activeFilterCount > 0"
                class="
                    absolute -right-2 -top-2
                    flex items-center justify-center
                    min-w-5 h-5 px-1
                    rounded-full
                    bg-brand
                    text-[10px] font-semibold
                    text-white
                "
                :aria-label="`${activeFilterCount} active filters`"
            >
                {{ activeFilterCount }}
            </span>
        </button>
    </div>

    <!-- MOBILE / ADVANCED FILTER MODAL -->
    <AppModal
        v-if="showFilters"
        :show="showFilters"
        mobile-position="bottom"
        desktop-position="center"
        max-width="4xl"
        @close="closeFilters"
    >
        <!-- HEADER -->
        <template #header>
            <div>
                <h3
                    class="
                        text-lg font-semibold
                        text-gray-900
                        dark:text-white
                    "
                >
                    {{ mobileTitle }}
                </h3>

                <p
                    class="
                        text-sm
                        text-gray-500
                        dark:text-gray-400
                    "
                >
                    Advanced filters
                </p>
            </div>
        </template>

        <!-- FILTERS -->
        <div class="flex flex-col gap-4">
            <!-- INLINE FILTERS -->
            <slot name="inline-filters" />

            <!-- MODAL FILTERS -->
            <slot name="modal-filters" />
        </div>

        <!-- FOOTER -->
        <template #footer>
            <div class="flex items-center gap-3">
                <!-- RESET -->
                <button
                    type="button"
                    class="
                        flex-1 h-12
                        rounded-2xl
                        border border-black/5
                        dark:border-white/10
                        text-gray-700
                        dark:text-gray-200
                        hover:bg-gray-50
                        dark:hover:bg-white/[0.05]
                        transition-colors
                    "
                    @click="resetFilters"
                >
                    Reset
                </button>

                <!-- APPLY -->
                <button
                    type="button"
                    class="
                        flex-1 h-12
                        rounded-2xl
                        bg-brand
                        text-white
                        hover:brightness-110
                        transition-all
                    "
                    @click="applyFilters"
                >
                    Apply
                </button>
            </div>
        </template>
    </AppModal>
</template>