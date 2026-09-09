```vue
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'

import {
    EllipsisVertical,
} from '@lucide/vue'

import type { Component } from 'vue'

export interface TableAction {
    label: string
    icon?: Component
    href?: string
    danger?: boolean
    onClick?: () => void
}

interface Props {
    actions: TableAction[]
}

const props = defineProps<Props>()
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <button
                type="button"
                class="
                    flex h-8 w-8 items-center justify-center
                    rounded-lg
                    hover:bg-gray-100
                    dark:hover:bg-white/[0.05]
                "
            >
                <EllipsisVertical class="h-4 w-4" />
            </button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end">
            <template
                v-for="(action, index) in props.actions"
                :key="index"
            >
                <!-- Link -->
                <DropdownMenuItem
                    v-if="action.href"
                    as-child
                    :class="
                        action.danger
                            ? 'text-red-500 focus:text-red-500'
                            : ''
                    "
                >
                    <Link :href="action.href">
                        <component
                            v-if="action.icon"
                            :is="action.icon"
                            class="mr-2 h-4 w-4"
                        />

                        {{ action.label }}
                    </Link>
                </DropdownMenuItem>

                <!-- Button -->
                <DropdownMenuItem
                    v-else
                    :class="
                        action.danger
                            ? 'text-red-500 focus:text-red-500'
                            : ''
                    "
                    @click="action.onClick?.()"
                >
                    <component
                        v-if="action.icon"
                        :is="action.icon"
                        class="mr-2 h-4 w-4"
                    />

                    {{ action.label }}
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
```
