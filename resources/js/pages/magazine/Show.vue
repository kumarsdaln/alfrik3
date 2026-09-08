<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'
import AppBadge from '@/Components/Ui/AppBadge.vue'

import {
    index as magazineIndex,
    show as magazineShow,
} from '@/routes/magazine'

import {
    show as magazineIssueShow,
} from '@/routes/magazine/issues'

import type {
    Magazine,
    MagazineIssue,
} from '@/types'

const props = defineProps<{
    magazine: Magazine
}>()

const magazine = props.magazine

const coverUrl = (issue: MagazineIssue) =>
    issue.cover_image || magazine.cover_image || '/frontend/images/placeholder.jpg'

const formatDate = (date?: string | null) => {
    if (!date) {
        return ''
    }

    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    })
}

const issueHref = (issue: MagazineIssue) =>
    magazineIssueShow([
        magazine.slug,
        issue.slug,
    ]).url
</script>

<template>
    <Head :title="`${magazine.title} — Alfrik Magazine`">
        <meta
            name="description"
            :content="
                magazine.meta_description ||
                magazine.subtitle ||
                `Explore ${magazine.title} on Alfrik Magazine.`
            "
        />

        <meta
            v-if="magazine.meta_keywords"
            name="keywords"
            :content="magazine.meta_keywords"
        />

        <link
            rel="canonical"
            :href="magazineShow(magazine.slug).url"
        />
    </Head>

    <!-- ================================================================
         Magazine Header
    ================================================================= -->

    <section>
        <div
            class="py-12 sm:py-16 lg:py-20"
        >
            <div
                class="grid gap-10 lg:grid-cols-[400px_1fr] lg:items-center lg:gap-16"
            >
                <!-- Magazine Cover -->

                <div
                    class="relative overflow-hidden border border-border-light dark:border-border-dark"
                >
                    <div class="aspect-[4/5]">
                        <img
                            :src="
                                magazine.cover_image ||
                                '/frontend/images/placeholder.jpg'
                            "
                            :alt="magazine.title"
                            class="h-full w-full object-cover"
                            @error="
                                ($event.target as HTMLImageElement).src =
                                    '/frontend/images/placeholder.jpg'
                            "
                        />
                    </div>

                    <div
                        v-if="magazine.featured"
                        class="absolute left-0 top-0"
                    >
                        <AppBadge
                            variant="primary"
                            size="sm"
                        >
                            Featured
                        </AppBadge>
                    </div>
                </div>

                <!-- Magazine Information -->

                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <AppText
                            v-if="magazine.category"
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            {{ magazine.category.name }}
                        </AppText>

                        <span
                            v-if="magazine.category"
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                        />

                        <AppText
                            v-if="magazine.issues?.length"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            {{ magazine.issues.length }}
                            {{ magazine.issues.length === 1 ? 'Issue' : 'Issues' }}
                        </AppText>
                    </div>

                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="5xl"
                        weight="normal"
                        leading="tight"
                        class="mt-5"
                    >
                        {{ magazine.title }}
                    </AppHeading>

                    <AppText
                        v-if="magazine.subtitle"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-6 max-w-3xl"
                    >
                        {{ magazine.subtitle }}
                    </AppText>

                    <AppText
                        v-if="magazine.content"
                        tag="div"
                        font="lora"
                        color="muted"
                        leading="relaxed"
                        class="prose prose-neutral dark:prose-invert mt-8 max-w-3xl"
                        v-html="magazine.content"
                    />

                    <div
                        v-if="magazine.author || magazine.published_at"
                        class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-2 border-y border-border-light py-4 dark:border-border-dark"
                    >
                        <AppText
                            v-if="magazine.author"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            By {{ magazine.author.name }}
                        </AppText>

                        <span
                            v-if="magazine.author && magazine.published_at"
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                        />

                        <AppText
                            v-if="magazine.published_at"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            Published {{ formatDate(magazine.published_at) }}
                        </AppText>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================
         Issues
    ================================================================= -->

    <section
        class="border-t border-border-light dark:border-border-dark"
    >
        <div
            class="container mx-auto px-4 py-14 sm:py-16 lg:py-20"
        >
            <div
                class="mb-10 flex flex-col gap-4 border-b border-border-light pb-6 dark:border-border-dark sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="primary"
                    >
                        Editions
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        class="mt-2"
                    >
                        Magazine Issues
                    </AppHeading>
                </div>

                <AppText
                    tag="p"
                    font="redhat"
                    size="xs"
                    color="muted"
                >
                    {{ magazine.issues?.length ?? 0 }}
                    {{ (magazine.issues?.length ?? 0) === 1 ? 'edition' : 'editions' }}
                </AppText>
            </div>

            <!-- Issues -->

            <div
                v-if="magazine.issues?.length"
                class="grid grid-cols-1 gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3"
            >
                <article
                    v-for="issue in magazine.issues"
                    :key="issue.id"
                    class="group"
                >
                    <Link
                        :href="issueHref(issue)"
                        class="block"
                    >
                        <div
                            class="relative overflow-hidden border border-border-light dark:border-border-dark"
                        >
                            <div class="aspect-[4/5] overflow-hidden">
                                <img
                                    :src="coverUrl(issue)"
                                    :alt="issue.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                                    @error="
                                        ($event.target as HTMLImageElement).src =
                                            '/frontend/images/placeholder.jpg'
                                    "
                                />
                            </div>

                            <div
                                v-if="issue.featured"
                                class="absolute left-0 top-0"
                            >
                                <AppBadge
                                    variant="primary"
                                    size="sm"
                                >
                                    Featured
                                </AppBadge>
                            </div>
                        </div>
                    </Link>

                    <div class="pt-5">
                        <AppText
                            v-if="issue.edition_label"
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            {{ issue.edition_label }}
                        </AppText>

                        <Link
                            :href="issueHref(issue)"
                            class="mt-2 block"
                        >
                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                hover-primary
                            >
                                {{ issue.title }}
                            </AppHeading>
                        </Link>

                        <AppText
                            v-if="issue.subtitle"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            :clamp="3"
                            class="mt-3"
                        >
                            {{ issue.subtitle }}
                        </AppText>

                        <div
                            class="mt-4 flex flex-wrap items-center gap-x-3 gap-y-1"
                        >
                            <AppText
                                v-if="issue.cover_date"
                                tag="span"
                                font="redhat"
                                size="xs"
                                color="muted"
                            >
                                {{ formatDate(issue.cover_date) }}
                            </AppText>

                            <span
                                v-if="
                                    issue.cover_date &&
                                    issue.articles?.length
                                "
                                class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                            />

                            <AppText
                                v-if="issue.articles?.length"
                                tag="span"
                                font="redhat"
                                size="xs"
                                color="muted"
                            >
                                {{ issue.articles.length }}
                                {{
                                    issue.articles.length === 1
                                        ? 'Article'
                                        : 'Articles'
                                }}
                            </AppText>
                        </div>
                    </div>
                </article>
            </div>

            <!-- No Issues -->

            <div
                v-else
                class="border border-dashed border-border-light px-6 py-24 text-center dark:border-border-dark"
            >
                <AppHeading
                    tag="p"
                    font="prata"
                    size="2xl"
                    weight="normal"
                    color="muted"
                >
                    No issues available.
                </AppHeading>

                <AppText
                    tag="p"
                    font="lora"
                    size="sm"
                    color="muted"
                    class="mt-3"
                >
                    Issues for this magazine will appear here when published.
                </AppText>
            </div>
        </div>
    </section>
</template>