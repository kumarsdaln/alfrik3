<script setup lang="ts">
    import { formatDate, type DateInput } from '@/utils/dateUtils'

    interface Props {
        value: DateInput
        options?: Intl.DateTimeFormatOptions | null
    }

    withDefaults(defineProps<Props>(), {
        options: null,
    })
</script>

<template>
    <time v-if="value" :datetime="String(value)">
        {{ formatDate(value, options) }}
    </time>

    <span v-else>—</span>
</template>