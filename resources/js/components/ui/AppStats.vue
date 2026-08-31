<script setup lang="ts">
    import type { Component } from 'vue'

    import { 
        Stat, 
        StatLabel, 
        StatValue, 
        StatDescription, 
        StatIcon 
    } from '@/components/ui/stat'

    export interface AppStatItem {
        label: string
        value: string | number
        description?: string
        icon?: Component
    }

    defineProps<{
        items: AppStatItem[]
    }>()
</script>

<template>
    <div class="
            grid
            grid-cols-1
            gap-px
            border
            border-border-light
            bg-border-light
            dark:border-border-dark
            dark:bg-border-dark
            sm:grid-cols-2
            lg:grid-cols-4
        ">
        <Stat v-for="item in items" :key="item.label">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <StatLabel :label="item.label" />

                    <StatValue :value="item.value" />
                </div>

                <StatIcon v-if="item.icon" :icon="item.icon" />
            </div>

            <StatDescription v-if="item.description" :description="item.description" />
        </Stat>
    </div>
</template>