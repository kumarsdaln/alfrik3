<script setup lang="ts">
import type { Component } from 'vue'

import AppEmptyState from '@/Components/Feedback/EmptyState/AppEmptyState.vue'
import DataNotFoundIcon from '@/Icons/DataNotFoundIcon.vue'

interface Props {
    title?: string
    description?: string
    icon?: Component
    colspan?: number
}

const props = withDefaults(
    defineProps<Props>(),
    {
        title: 'No Data Found',
        description: 'There are no records available right now.',
        icon: DataNotFoundIcon,
        colspan: 1,
    }
)
</script>

<template>
    <tbody>
        <tr>
            <td
                :colspan="colspan"
                class="px-6 py-24 text-center"
            >
                <AppEmptyState
                    :title="title"
                    :description="description"
                >
                    <template #icon>
                        <component
                            :is="icon"
                            class="h-16 w-16"
                        />
                    </template>
                </AppEmptyState>
            </td>
        </tr>
    </tbody>
</template>