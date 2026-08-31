<script setup lang="ts">
    import { Check } from '@lucide/vue'

    import {
        ComboboxItem,
        type ComboboxItemProps,
    } from 'reka-ui'

    const props = defineProps<ComboboxItemProps>()
</script>

<template>
    <ComboboxItem v-bind="props" class="
            group
            relative
            flex
            w-full
            cursor-default
            select-none
            items-center
            rounded-md
            px-3
            py-2
            text-sm
            outline-none

            transition-colors
            duration-100

            data-highlighted:bg-muted
            data-highlighted:text-foreground

            data-disabled:pointer-events-none
            data-disabled:opacity-50
        ">
        <!-- Checkbox -->
        <span class="
                mr-3
                flex
                size-[17px]
                shrink-0
                items-center
                justify-center
                rounded-[4px]
                border
                border-border
                bg-background

                transition-all
                duration-150

                group-data-[state=checked]:border-foreground
                group-data-[state=checked]:bg-foreground
                group-data-[state=checked]:text-background
            ">
            <Check :size="11" :stroke-width="2.5" class="
                    opacity-0
                    transition-opacity
                    duration-150

                    group-data-[state=checked]:opacity-100
                " />
        </span>

        <!-- Label -->
        <span class="min-w-0 flex-1 truncate">
            <slot />
        </span>
    </ComboboxItem>
</template>