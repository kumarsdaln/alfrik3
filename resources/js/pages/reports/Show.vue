<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, Link } from '@inertiajs/vue3'
    import {
        CalendarDays,
        ArrowLeft,
    } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import ReportCard from '@/components/reports/ReportCard.vue'
    import { MetaInfo } from '@/components/ui/metainfo'

    import {
        show as reportShow,
    } from '@/routes/reports'

    import type {
        Report,
        ReportContentBlock,
        ReportSection,
    } from '@/types'


    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        report: Report
        related?: Report[]

        meta_data?: {
            meta_title?: string
            meta_description?: string
            canonical_url?: string
            og_title?: string
            og_description?: string
            og_image?: string
            twitter_title?: string
            twitter_description?: string
            twitter_image?: string
        }
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    const dateLabel = computed(() => {
        const date =
            props.report.published_at ||
            props.report.report_date ||
            props.report.created_at

        if (!date) {
            return ''
        }

        return new Date(date).toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric',
        })
    })


    /*
    |--------------------------------------------------------------------------
    | Report URL
    |--------------------------------------------------------------------------
    */

    const reportUrl = computed(() =>
        reportShow(props.report.slug).url,
    )


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const blockType = (block: ReportContentBlock) =>
        block.type?.value ?? ''


    const blockContent = (
        block: ReportContentBlock,
    ): Record<string, unknown> => {
        return block.content ?? {}
    }


    const stringValue = (
        value: unknown,
    ): string => {
        if (
            typeof value === 'string' ||
            typeof value === 'number'
        ) {
            return String(value)
        }

        return ''
    }


    const stringArray = (
        value: unknown,
    ): string[] => {
        if (!Array.isArray(value)) {
            return []
        }

        return value
            .map(item => String(item))
            .filter(Boolean)
    }


    const sectionBlocks = (
        section: ReportSection,
    ) => {
        return section.content_blocks ?? []
    }
</script>


<template>

    <Head :title="meta_data?.meta_title || report.title">
        <meta name="description" :content="meta_data?.meta_description ??
            report.summary ??
            ''
            " />

        <meta v-if="meta_data?.canonical_url" head-key="canonical" rel="canonical" :href="meta_data.canonical_url" />

        <meta v-if="meta_data?.og_title" property="og:title" :content="meta_data.og_title" />

        <meta v-if="meta_data?.og_description" property="og:description" :content="meta_data.og_description" />

        <meta v-if="meta_data?.og_image" property="og:image" :content="meta_data.og_image" />

        <meta v-if="meta_data?.twitter_title" name="twitter:title" :content="meta_data.twitter_title" />

        <meta v-if="meta_data?.twitter_description" name="twitter:description"
            :content="meta_data.twitter_description" />

        <meta v-if="meta_data?.twitter_image" name="twitter:image" :content="meta_data.twitter_image" />
    </Head>


    <!-- ================================================================== -->
    <!-- Report Header -->
    <!-- ================================================================== -->

    <header class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                container
                mx-auto
                px-4
                pb-10
                pt-12
                sm:pb-12
                sm:pt-16
                lg:pb-14
                lg:pt-20
            ">
            <!-- Back -->
            <Link href="/reports" class="
                    mb-8
                    inline-flex
                    items-center
                    gap-2
                    text-xs
                    font-medium
                    text-muted-foreground
                    transition-colors
                    hover:text-foreground
                ">
                <ArrowLeft :size="14" :stroke-width="1.8" />

                Back to reports
            </Link>


            <!-- Type -->

            <AppText v-if="report.type" tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary"
                class="mb-3">
                {{ report.type.label }}
            </AppText>


            <!-- Title -->

            <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight" class="max-w-5xl">
                {{ report.title }}
            </AppHeading>


            <!-- Subtitle -->

            <AppText v-if="report.subtitle" tag="p" font="lora" size="xl" color="muted" leading="relaxed"
                class="mt-4 max-w-3xl">
                {{ report.subtitle }}
            </AppText>


            <!-- Summary -->

            <AppText v-if="report.summary" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                class="mt-5 max-w-3xl">
                {{ report.summary }}
            </AppText>


            <!-- Meta -->

            <MetaInfo class="mt-7" :items="[
                ...(report.author
                    ? [
                        {
                            label: 'By',
                            value: report.author.name,
                        },
                    ]
                    : []),
                ...(dateLabel
                    ? [
                        {
                            value: dateLabel,
                            icon: CalendarDays,
                        },
                    ]
                    : []),
                ...(report.research
                    ? [
                        {
                            label: 'Research',
                            value: report.research.title,
                        },
                    ]
                    : []),
            ]" />
        </div>
    </header>


    <!-- ================================================================== -->
    <!-- Report Content -->
    <!-- ================================================================== -->

    <main>
        <div class="
                container
                mx-auto
                px-4
                py-12
                sm:py-16
                lg:py-20
            ">
            <div class="
                    mx-auto
                    max-w-4xl
                ">

                <!-- Report Description -->

                <section v-if="report.description" class="mb-14">
                    <AppText tag="p" font="lora" size="lg" leading="relaxed" class="whitespace-pre-line">
                        {{ report.description }}
                    </AppText>
                </section>


                <!-- ====================================================== -->
                <!-- Sections -->
                <!-- ====================================================== -->

                <div v-if="report.sections?.length" class="space-y-20">
                    <section v-for="section in report.sections" :key="section.id">
                        <!-- Section Header -->

                        <header class="mb-8">
                            <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-3">
                                Section {{ section.position + 1 }}
                            </AppText>

                            <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight">
                                {{ section.title }}
                            </AppHeading>

                            <AppText v-if="section.subtitle" tag="p" font="lora" size="lg" color="muted"
                                leading="relaxed" class="mt-3">
                                {{ section.subtitle }}
                            </AppText>
                        </header>


                        <!-- Section Content -->

                        <div v-if="section.content" class="
                                mb-8
                                whitespace-pre-line
                                text-base
                                leading-8
                                text-foreground
                            ">
                            {{ section.content }}
                        </div>


                        <!-- Content Blocks -->

                        <div v-if="sectionBlocks(section).length" class="space-y-10">
                            <article v-for="block in sectionBlocks(section)" :key="block.id">

                                <!-- ================================================= -->
                                <!-- TEXT -->
                                <!-- ================================================= -->

                                <div v-if="blockType(block) === 'text'" class="
                                        whitespace-pre-line
                                        text-base
                                        leading-8
                                        text-foreground
                                    ">
                                    {{
                                        stringValue(
                                            blockContent(block).text,
                                        )
                                    }}
                                </div>


                                <!-- ================================================= -->
                                <!-- HEADING -->
                                <!-- ================================================= -->

                                <component :is="`h${Math.min(
                                    4,
                                    Math.max(
                                        2,
                                        Number(
                                            blockContent(block).level ?? 2,
                                        ),
                                    ),
                                )}`
                                    " v-else-if="
                                        blockType(block) === 'heading'
                                    " class="
                                        font-prata
                                        text-2xl
                                        font-normal
                                        leading-tight
                                    ">
                                    {{
                                        stringValue(
                                            blockContent(block).text,
                                        )
                                    }}
                                </component>


                                <!-- ================================================= -->
                                <!-- QUOTE -->
                                <!-- ================================================= -->

                                <figure v-else-if="
                                    blockType(block) === 'quote'
                                " class="
                                        border-l-2
                                        border-primary
                                        pl-6
                                    ">
                                    <blockquote class="
                                            font-lora
                                            text-xl
                                            italic
                                            leading-relaxed
                                        ">
                                        {{
                                            stringValue(
                                                blockContent(block).quote,
                                            )
                                        }}
                                    </blockquote>

                                    <figcaption v-if="
                                        blockContent(block).author ||
                                        blockContent(block).source
                                    " class="
                                            mt-4
                                            text-sm
                                            text-muted-foreground
                                        ">
                                        <span v-if="
                                            blockContent(block).author
                                        ">
                                            {{
                                                stringValue(
                                                    blockContent(block).author,
                                                )
                                            }}
                                        </span>

                                        <span v-if="
                                            blockContent(block).author &&
                                            blockContent(block).source
                                        ">
                                            ·
                                        </span>

                                        <span v-if="
                                            blockContent(block).source
                                        ">
                                            {{
                                                stringValue(
                                                    blockContent(block).source,
                                                )
                                            }}
                                        </span>
                                    </figcaption>
                                </figure>


                                <!-- ================================================= -->
                                <!-- IMAGE -->
                                <!-- ================================================= -->

                                <figure v-else-if="
                                    blockType(block) === 'image'
                                ">
                                    <img v-if="
                                        blockContent(block).url
                                    " :src="stringValue(
                                            blockContent(block).url,
                                        )
                                            " :alt="stringValue(
                                            blockContent(block).alt ||
                                            block.title ||
                                            report.title,
                                        )
                                            " class="
                                            w-full
                                            object-cover
                                        " />

                                    <figcaption v-if="
                                        blockContent(block).caption
                                    " class="
                                            mt-3
                                            text-sm
                                            text-muted-foreground
                                        ">
                                        {{
                                            stringValue(
                                                blockContent(block).caption,
                                            )
                                        }}
                                    </figcaption>
                                </figure>


                                <!-- ================================================= -->
                                <!-- TABLE -->
                                <!-- ================================================= -->

                                <div v-else-if="
                                    blockType(block) === 'table'
                                " class="overflow-x-auto">
                                    <table class="
                                            w-full
                                            border-collapse
                                            text-left
                                            text-sm
                                        ">
                                        <thead>
                                            <tr>
                                                <th v-for="column in stringArray(
                                                    blockContent(block).columns,
                                                )" :key="column" class="
                                                        border-b
                                                        border-border-light
                                                        px-4
                                                        py-3
                                                        font-semibold
                                                        dark:border-border-dark
                                                    ">
                                                    {{ column }}
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(
row,
                                                        rowIndex
                                                ) in stringArray(
                                                            blockContent(block).rows,
                                                        )" :key="rowIndex">
                                                <td v-for="(
cell,
                                                            cellIndex
                                                    ) in row.split(',')" :key="cellIndex" class="
                                                        border-b
                                                        border-border-light
                                                        px-4
                                                        py-3
                                                        dark:border-border-dark
                                                    ">
                                                    {{ cell.trim() }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <!-- ================================================= -->
                                <!-- STATISTIC -->
                                <!-- ================================================= -->

                                <div v-else-if="
                                    blockType(block) === 'statistic'
                                " class="
                                        border-y
                                        border-border-light
                                        py-8
                                        dark:border-border-dark
                                    ">
                                    <div class="
                                            font-prata
                                            text-5xl
                                            font-normal
                                            leading-none
                                        ">
                                        {{
                                            stringValue(
                                                blockContent(block).value,
                                            )
                                        }}
                                    </div>

                                    <AppText v-if="
                                        blockContent(block).label
                                    " size="sm" weight="medium" class="mt-3">
                                        {{
                                            stringValue(
                                                blockContent(block).label,
                                            )
                                        }}
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).source
                                    " size="xs" color="muted" class="mt-2">
                                        Source:
                                        {{
                                            stringValue(
                                                blockContent(block).source,
                                            )
                                        }}
                                    </AppText>
                                </div>


                                <!-- ================================================= -->
                                <!-- CHART -->
                                <!-- ================================================= -->

                                <div v-else-if="
                                    blockType(block) === 'chart'
                                " class="
                                        border
                                        border-border-light
                                        p-6
                                        dark:border-border-dark
                                    ">
                                    <AppText v-if="block.title" tag="h3" size="sm" weight="bold">
                                        {{ block.title }}
                                    </AppText>

                                    <div class="
                                            mt-6
                                            grid
                                            gap-3
                                        ">
                                        <div v-for="(
label,
                                                    index
                                            ) in stringArray(
                                                        blockContent(block).labels,
                                                    )" :key="label" class="
                                                grid
                                                grid-cols-[minmax(100px,1fr)_2fr_auto]
                                                items-center
                                                gap-3
                                                text-sm
                                            ">
                                            <span>
                                                {{ label }}
                                            </span>

                                            <div class="
                                                    h-2
                                                    bg-muted
                                                    overflow-hidden
                                                ">
                                                <div class="
                                                        h-full
                                                        bg-primary
                                                    " :style="{
                                                        width: `${Math.min(
                                                            100,
                                                            Math.max(
                                                                0,
                                                                Number(
                                                                    stringArray(
                                                                        blockContent(
                                                                            block,
                                                                        ).values,
                                                                    )[index] ?? 0,
                                                                ),
                                                            ),
                                                        )}%`,
                                                    }" />
                                            </div>

                                            <span class="
                                                    tabular-nums
                                                    text-muted-foreground
                                                ">
                                                {{
                                                    stringArray(
                                                        blockContent(block).values,
                                                    )[index]
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <!-- ================================================= -->
                                <!-- FINDING -->
                                <!-- ================================================= -->

                                <div v-else-if="
                                    blockType(block) === 'finding'
                                " class="
                                        border-l-2
                                        border-primary
                                        pl-6
                                    ">
                                    <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                        Key Finding
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).finding
                                    " font="lora" size="lg" leading="relaxed" class="mt-3">
                                        {{
                                            stringValue(
                                                blockContent(block).finding,
                                            )
                                        }}
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).evidence
                                    " size="sm" color="muted" leading="relaxed" class="mt-3">
                                        {{
                                            stringValue(
                                                blockContent(block).evidence,
                                            )
                                        }}
                                    </AppText>
                                </div>


                                <!-- ================================================= -->
                                <!-- RECOMMENDATION -->
                                <!-- ================================================= -->

                                <div v-else-if="
                                    blockType(block) ===
                                    'recommendation'
                                " class="
                                        border
                                        border-border-light
                                        p-6
                                        dark:border-border-dark
                                    ">
                                    <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                        Recommendation
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).recommendation
                                    " font="lora" size="lg" leading="relaxed" class="mt-3">
                                        {{
                                            stringValue(
                                                blockContent(block)
                                                    .recommendation,
                                            )
                                        }}
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).rationale
                                    " size="sm" color="muted" leading="relaxed" class="mt-3">
                                        {{
                                            stringValue(
                                                blockContent(block).rationale,
                                            )
                                        }}
                                    </AppText>
                                </div>


                                <!-- ================================================= -->
                                <!-- Block Description -->
                                <!-- ================================================= -->

                                <AppText v-if="
                                    block.description &&
                                    ![
                                        'text',
                                        'quote',
                                        'finding',
                                        'recommendation',
                                    ].includes(blockType(block))
                                " size="sm" color="muted" leading="relaxed" class="mt-4">
                                    {{ block.description }}
                                </AppText>

                            </article>
                        </div>
                    </section>
                </div>


                <!-- No sections -->

                <section v-else-if="
                    !report.description &&
                    !report.summary
                " class="
                        border
                        border-dashed
                        border-border-light
                        p-10
                        text-center
                        dark:border-border-dark
                    ">
                    <AppText size="sm" color="muted">
                        This report does not have published content yet.
                    </AppText>
                </section>

            </div>
        </div>
    </main>


    <!-- ================================================================== -->
    <!-- Related Reports -->
    <!-- ================================================================== -->

    <section v-if="related?.length" class="
            border-t
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                container
                mx-auto
                px-4
                py-12
                sm:py-14
                lg:py-16
            ">
            <div class="mb-8">
                <AppText tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-3">
                    Continue reading
                </AppText>

                <AppHeading tag="h2" font="prata" size="3xl" weight="normal">
                    Related reports
                </AppHeading>
            </div>

            <div class="
                    grid
                    grid-cols-1
                    gap-x-8
                    gap-y-12
                    sm:grid-cols-2
                    lg:grid-cols-3
                    lg:gap-x-10
                ">
                <ReportCard v-for="relatedReport in related" :key="relatedReport.id" :report="relatedReport" :href="reportShow(
                    relatedReport.slug,
                ).url" />
            </div>
        </div>
    </section>
</template>