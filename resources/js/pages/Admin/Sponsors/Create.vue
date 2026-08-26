<script setup lang="ts">
    import { Head } from '@inertiajs/vue3'
    import SponsorForm from './SponsorForm.vue'

    defineProps<{ tiers?: string[] }>()
</script>

<template>
    <Head title="Add sponsor" />
    <SponsorForm :tiers="tiers" />
</template>
