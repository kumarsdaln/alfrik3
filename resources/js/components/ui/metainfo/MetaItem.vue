<script setup lang="ts">
    import type { Component } from 'vue'

    import AppText from '@/Components/Ui/AppText.vue'

    defineProps<{
        value: string
        label?: string
        icon?: Component
        href?: string
    }>()
</script>

<template>
    <!-- Link -->
    <a v-if="href" :href="href" target="_blank" rel="noopener noreferrer" class="
            inline-flex
            items-center
            gap-1.5
            text-brand
            transition-opacity
            hover:opacity-75
        ">
        <AppText tag="span" size="xs" color="brand">
            {{ value }}
        </AppText>

        <component v-if="icon" :is="icon" :size="14" :stroke-width="1.7" class="shrink-0" />
    </a>

    <!-- Normal Metadata -->

    <div v-else class="flex items-center gap-2">
        <component v-if="icon" :is="icon" :size="13" :stroke-width="1.7" class="shrink-0" />

        <AppText v-if="label" tag="span" size="xs" color="muted">
            {{ label }}
        </AppText>

        <AppText tag="span" size="xs" :weight="label ? 'medium' : 'normal'" color="muted">
            {{ value }}
        </AppText>
    </div>
</template>