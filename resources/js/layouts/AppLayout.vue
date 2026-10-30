
<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue'
import SeoMetaData from '@/components/seo/SeoMetaData.vue'

import type { BreadcrumbItem } from '@/types'
import type { SeoMetadata } from '@/types/seo'

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[]
        seoMetadata?: SeoMetadata | null
    }>(),
    {
        breadcrumbs: () => [],
        seoMetadata: null,
    },
)
</script>

<template>
    <SeoMetaData :seoMetadata="seoMetadata" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>