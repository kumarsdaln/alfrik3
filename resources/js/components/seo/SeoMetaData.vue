<script setup lang="ts">
    import { computed } from 'vue'
    import { Head } from '@inertiajs/vue3'

    import type { SeoMetadata } from '@/types/seo'

    const props = withDefaults(
        defineProps<{
            seoMetadata?: SeoMetadata | null
            fallbackTitle?: string
            fallbackDescription?: string
            fallbackImage?: string | null
            siteName?: string
        }>(),
        {
            seoMetadata: null,
            fallbackTitle: 'Alfrik',
            fallbackDescription: '',
            fallbackImage: null,
            siteName: 'Alfrik',
        },
    )

    const title = computed(
        () => props.seoMetadata?.title || props.fallbackTitle,
    )

    const description = computed(
        () => props.seoMetadata?.description || props.fallbackDescription,
    )

    const canonicalUrl = computed(
        () => props.seoMetadata?.canonical_url || undefined,
    )

    const robots = computed(() => {
        const indexable = props.seoMetadata?.indexable ?? true
        const followable = props.seoMetadata?.followable ?? true

        return `${indexable ? 'index' : 'noindex'}, ${followable ? 'follow' : 'nofollow'}`
    })

    const ogTitle = computed(
        () => props.seoMetadata?.og_title || title.value,
    )

    const ogDescription = computed(
        () => props.seoMetadata?.og_description || description.value,
    )

    const ogImage = computed(
        () => props.seoMetadata?.og_image_url || props.fallbackImage || undefined,
    )

    const twitterTitle = computed(
        () => props.seoMetadata?.twitter_title || ogTitle.value,
    )

    const twitterDescription = computed(
        () => props.seoMetadata?.twitter_description || ogDescription.value,
    )

    const twitterImage = computed(
        () => props.seoMetadata?.twitter_image_url || ogImage.value,
    )

    const twitterCard = computed(
        () => props.seoMetadata?.twitter_card || 'summary_large_image',
    )

    const structuredData = computed(() => {
        const schemaType = props.seoMetadata?.schema_type

        if (!schemaType) {
            return null
        }

        return JSON.stringify({
            '@context': 'https://schema.org',
            '@type': schemaType,
            name: title.value,
            headline: title.value,
            description: description.value || undefined,
            url: canonicalUrl.value,
            image: ogImage.value,
            inLanguage: props.seoMetadata?.locale || undefined,
        })
    })
</script>

<template>

    <Head :title="title">
        <!-- Search engines -->
        <meta head-key="description" name="description" :content="description" />

        <meta head-key="robots" name="robots" :content="robots" />

        <link v-if="canonicalUrl" head-key="canonical" rel="canonical" :href="canonicalUrl" />

        <!-- Open Graph -->
        <meta head-key="og:title" property="og:title" :content="ogTitle" />

        <meta head-key="og:description" property="og:description" :content="ogDescription" />

        <meta head-key="og:type" property="og:type" :content="props.seoMetadata?.og_type || 'website'" />

        <meta head-key="og:site_name" property="og:site_name" :content="siteName" />

        <meta v-if="canonicalUrl" head-key="og:url" property="og:url" :content="canonicalUrl" />

        <meta v-if="ogImage" head-key="og:image" property="og:image" :content="ogImage" />

        <meta v-if="seoMetadata?.locale" head-key="og:locale" property="og:locale" :content="seoMetadata.locale" />

        <!-- X / Twitter -->
        <meta head-key="twitter:card" name="twitter:card" :content="twitterCard" />

        <meta head-key="twitter:title" name="twitter:title" :content="twitterTitle" />

        <meta head-key="twitter:description" name="twitter:description" :content="twitterDescription" />

        <meta v-if="twitterImage" head-key="twitter:image" name="twitter:image" :content="twitterImage" />

        <!-- Structured data -->
        <component v-if="structuredData" :is="'script'" head-key="structured-data" type="application/ld+json"
            v-html="structuredData" />
    </Head>
</template>
