<script setup lang="ts">
    import { Check } from '@lucide/vue'

    import {
        ComboboxItem,
        type ComboboxItemProps,
    } from 'reka-ui'

    const props = defineProps<ComboboxItemProps>()
</script>

<template>
    <ComboboxItem v-bind="props" 
         class="
            group
            flex
            w-full
            cursor-default
            select-none
            items-center
            px-3
            py-2
            text-sm
            text-content
            outline-none
            transition-colors

            data-[highlighted]:bg-secondary/10
            data-[highlighted]:text-content

            data-[disabled]:pointer-events-none
            data-[disabled]:opacity-40
        ">
        <!-- Checkbox -->
        <span class="
                mr-3
                flex
                size-4
                shrink-0
                items-center
                justify-center
                border
                border-border
                bg-surface
                text-foreground

                group-data-[state=checked]:
                    border-primary

                group-data-[state=checked]:
                    bg-primary
            ">
            <Check :size="11" :stroke-width="2.5"  class="
                    opacity-0
                    group-data-[state=checked]:opacity-100
                " />
        </span>

        <!-- Label -->
        <span class="min-w-0 flex-1 truncate">
            <slot />
        </span>
    </ComboboxItem>
</template>