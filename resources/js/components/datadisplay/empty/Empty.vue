<script setup lang="ts">
    import AppText from '@/components/ui/AppText.vue'

    withDefaults(
        defineProps<{
            title?: string
            description?: string
        }>(),
        {
            title: 'No research found.',
            description: 'Try a different research type or search term.',
        },
    )
</script>

<template>
    <div class="border border-dashed border-border-light py-28 text-center dark:border-border-dark">
        <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="mb-3 italic">
            {{ title }}
        </AppText>

        <AppText tag="p" size="sm" color="muted" align="center">
            {{ description }}
        </AppText>
    </div>
</template>
