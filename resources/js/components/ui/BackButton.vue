<script setup lang="ts">
    import { ArrowLeft } from '@lucide/vue';
    import { Button } from './button';

    const back = () => {
        history.back();
    }
</script>
<template>
    <Button variant="outline" size="icon" @click="back()">
        <ArrowLeft class="size-4" />
    </Button>
</template>