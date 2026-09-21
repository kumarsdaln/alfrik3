<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { store } from '@/routes/admin/seo'
    import type { FormOption, SeoMetadata } from '@/types'
    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        model: {
            type: string
            id: number
            title: string
        }

        seo: {
            data: SeoMetadata | null
        }

        localeOptions: FormOption[]
        schemaTypeOptions: FormOption[]
        ogTypeOptions: FormOption[]
        twitterCardOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="my-4">
        <Heading title="SEO" :description="`Manage SEO metadata for ${props.model.title}.`" />

        <Form v-slot="{ errors, processing }" v-bind="store.form({
            type: props.model.type,
            id: props.model.id,
        })" class="mt-6 space-y-8">
            <!-- General SEO -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-base font-semibold text-foreground">
                        General SEO
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Configure the primary search engine metadata for this page.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Locale" required :error="errors.locale">
                        <AppSelect 
                            name="locale" 
                            placeholder="Select locale" 
                            :options="localeOptions"
                            :default-value="seo.data?.locale ?? 'en-IN'" />
                    </AppFormControl>
                    <AppFormControl label="Canonical URL" :error="errors.canonical_url">
                        <AppInput 
                            name="canonical_url" 
                            placeholder="https://example.com/page"
                            :default-value="seo.data?.canonical_url ?? ''" />
                    </AppFormControl>
                </div>

                <AppFormControl label="SEO Title" :error="errors.title">
                    <AppInput 
                        name="title" 
                        placeholder="Enter SEO title" 
                        :default-value="seo.data?.title ?? ''" />
                </AppFormControl>

                <AppFormControl label="SEO Description" :error="errors.description">
                    <AppTextarea 
                        name="description" 
                        placeholder="Enter SEO description"
                        :default-value="seo.data?.description ?? ''" />
                </AppFormControl>
            </section>

            <!-- Open Graph -->
            <section class="space-y-4 border-t border-border-light pt-6 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold text-foreground">
                        Open Graph
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Configure how this page appears when shared on social platforms.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="OG Type" :error="errors.og_type">
                        <AppSelect 
                            name="og_type" 
                            placeholder="Select Open Graph type" 
                            :options="ogTypeOptions"
                            :default-value="seo.data?.og_type" />
                    </AppFormControl>

                    <AppFormControl label="OG Image URL" :error="errors.og_image_url">
                        <AppInput 
                            name="og_image_url" 
                            placeholder="https://example.com/image.jpg"
                            :default-value="seo.data?.og_image_url??''" />
                    </AppFormControl>
                </div>

                <AppFormControl label="OG Title" :error="errors.og_title">
                    <AppInput 
                        name="og_title" 
                        placeholder="Enter Open Graph title"
                        :default-value="seo.data?.og_title??''" />
                </AppFormControl>

                <AppFormControl label="OG Description" :error="errors.og_description">
                    <AppTextarea 
                        name="og_description" 
                        placeholder="Enter Open Graph description"
                        :default-value="seo.data?.og_description??''" />
                </AppFormControl>
            </section>

            <!-- Twitter -->
            <section class="space-y-4 border-t border-border-light pt-6 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold text-foreground">
                        Twitter / X
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Configure how this page appears when shared on Twitter or X.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Twitter Card" :error="errors.twitter_card">
                        <AppSelect 
                            name="twitter_card" 
                            placeholder="Select Twitter card" :options="twitterCardOptions"
                            :default-value="seo.data?.twitter_card" />
                    </AppFormControl>

                    <AppFormControl label="Twitter Image URL" :error="errors.twitter_image_url">
                        <AppInput name="twitter_image_url" placeholder="https://example.com/image.jpg"
                            :default-value="seo.data?.twitter_image_url ?? ''" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Twitter Title" :error="errors.twitter_title">
                    <AppInput name="twitter_title" placeholder="Enter Twitter title"
                        :default-value="seo.data?.twitter_title ?? ''" />
                </AppFormControl>

                <AppFormControl label="Twitter Description" :error="errors.twitter_description">
                    <AppTextarea name="twitter_description" placeholder="Enter Twitter description"
                        :default-value="seo.data?.twitter_description ?? ''" />
                </AppFormControl>
            </section>

            <!-- Structured Data -->
            <section class="space-y-4 border-t border-border-light pt-6 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold text-foreground">
                        Structured Data
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Define the Schema.org type used to describe this page.
                    </p>
                </div>

                <AppFormControl label="Schema Type" :error="errors.schema_type">
                    <AppSelect name="schema_type" placeholder="Select schema type" :options="schemaTypeOptions"
                        :default-value="seo.data?.schema_type" />
                </AppFormControl>
            </section>

            <!-- Search Engine Controls -->
            <section class="space-y-4 border-t border-border-light pt-6 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold text-foreground">
                        Search Engine Controls
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Control whether search engines can index and follow this page.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Indexable" :error="errors.indexable">
                        <AppSelect name="indexable" :options="[
                            { value: '1', label: 'Yes' },
                            { value: '0', label: 'No' },
                        ]" :default-value="seo.data?.indexable === false ? '0' : '1'
                                " />
                    </AppFormControl>

                    <AppFormControl label="Followable" :error="errors.followable">
                        <AppSelect name="followable" :options="[
                            { value: '1', label: 'Yes' },
                            { value: '0', label: 'No' },
                        ]" :default-value="seo.data?.followable === false ? '0' : '1'
                                " />
                    </AppFormControl>
                </div>
            </section>

            <!-- Actions -->
            <div
                class="flex flex-col-reverse gap-3 border-t border-border-light pt-6 sm:flex-row sm:justify-end dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="`/admin/${props.model.type}`">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Save SEO' }}
                </Button>
            </div>
        </Form>
    </div>
</template>