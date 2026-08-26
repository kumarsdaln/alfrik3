<script setup lang="ts">
    import { Head } from '@inertiajs/vue3'
    import SponsorForm from './SponsorForm.vue'
    import type { Sponsor } from '@/types/sponsor'

    defineProps<{
        sponsor: Sponsor
        tiers?: string[]
    }>()
</script>

<template>
    <Head title="Edit sponsor" />
    <SponsorForm :sponsor="sponsor" :tiers="tiers" />
</template>
