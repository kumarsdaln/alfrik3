<script setup>
    defineProps({
        lines: {
            type: Number,
            default: 3,
        }
    })
</script>

<template>
    <div class="space-y-3">
        <AppSkeleton 
            v-for="line in lines" 
            :key="line" height="h-4" 
            :width="line === lines ? 'w-2/3' : 'w-full' " 
        />
    </div>
</template>