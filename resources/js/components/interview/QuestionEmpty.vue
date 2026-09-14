<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue'

const emit = defineEmits<{
    add: []
}>()
</script>

<template>
    <div
        class="flex min-h-64 flex-col items-center justify-center p-10 text-center"
    >
        <p class="text-sm font-medium">
            No questions yet
        </p>

        <p class="mt-1 max-w-sm text-sm text-muted-foreground">
            Add questions to start building this interview.
        </p>

        <Button
            type="button"
            class="mt-4"
            @click="emit('add')"
        >
            Add First Question
        </Button>
    </div>
</template>