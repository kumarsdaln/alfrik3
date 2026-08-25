<script setup>
    defineProps({
        size: {
            type: String,
            default: 'h-12 w-12',
        }
    })
</script>

<template>
    <AppSkeleton 
        :height="size.split(' ')[0]" 
        :width="size.split(' ')[1]" 
        rounded="full" 
    />
</template>