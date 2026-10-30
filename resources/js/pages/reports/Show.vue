<script setup lang="ts">
    import { computed } from 'vue'
    import { Head } from '@inertiajs/vue3'

    import {
        CalendarDays,
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
        SeoMetadata,
    } from '@/types'


    interface Props {
        report: {
            data: Report
        }
        related?: Report[]
        meta_data?: SeoMetadata
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    const dateLabel = computed(() => {
        const date =
            props.report.data.published_at ??
            props.report.data.report_date ??
            props.report.data.created_at

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
        reportShow(props.report.data.slug).url,
    )


    /*
    |--------------------------------------------------------------------------
    | Content Block Helpers
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
    ): ReportContentBlock[] => {
        return section.content_blocks ?? []
    }


    /*
    |--------------------------------------------------------------------------
    | Section Number
    |--------------------------------------------------------------------------
    */

    const sectionNumber = (
        section: ReportSection,
    ) => {
        return String(section.position + 1).padStart(2, '0')
    }
</script>


<template>

    <Head :title="meta_data?.meta_title || report.data.title">
        <meta name="description" :content="meta_data?.meta_description ??
            report.data.summary ??
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


    <!-- ================================================================ -->
    <!-- Report Header -->
    <!-- ================================================================ -->

    <header class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                container
                pb-10
                pt-8
                sm:pb-12
                sm:pt-12
                lg:pb-14
                lg:pt-16
            ">


            <!-- Type -->
            <AppText v-if="report.data.type" tag="p" size="xs" weight="bold" tracking="wide" uppercase color="brand"
                class="mb-3">
                {{ report.data.type.label }}
            </AppText>


            <!-- Title -->

            <AppHeading tag="h1" font="prata" size="4xl" weight="normal" leading="tight" class="max-w-4xl sm:text-5xl">
                {{ report.data.title }}
            </AppHeading>


            <!-- Subtitle -->

            <AppText v-if="report.data.subtitle" tag="p" font="lora" size="xl" color="muted" leading="relaxed"
                class="mt-4 max-w-3xl">
                {{ report.data.subtitle }}
            </AppText>


            <!-- Summary -->

            <AppText v-if="report.data.summary" tag="p" font="lora" size="sm" color="muted" leading="relaxed"
                class="mt-4 max-w-3xl">
                {{ report.data.summary }}
            </AppText>


            <!-- Meta -->

            <MetaInfo class="mt-6" :items="[
                ...(report.data.author
                    ? [
                        {
                            label: 'By',
                            value: report.data.author.name,
                        },
                    ]
                    : []),

                ...(dateLabel
                    ? [
                        {
                            label: 'Published',
                            value: dateLabel,
                            icon: CalendarDays,
                        },
                    ]
                    : []),

                ...(report.data.research
                    ? [
                        {
                            label: 'Research',
                            value: report.data.research.title,
                        },
                    ]
                    : []),
            ]" />
        </div>
    </header>


    <!-- ================================================================ -->
    <!-- Report Content -->
    <!-- ================================================================ -->

    <main>
        <div class="
                container
                py-10
                sm:py-14
                lg:py-16
            ">
            <div>
                <!-- ==================================================== -->
                <!-- Description -->
                <!-- ==================================================== -->

                <section v-if="report.data.description" class="mb-12">
                    <AppText tag="p" font="lora" size="lg" leading="relaxed" class="whitespace-pre-line">
                        {{ report.data.description }}
                    </AppText>
                </section>


                <!-- ==================================================== -->
                <!-- Sections -->
                <!-- ==================================================== -->

                <div v-if="report.data.sections?.length" class="space-y-16">
                    <section v-for="section in report.data.sections" :key="section.id">
                        <!-- Section Header -->

                        <header class="mb-7">
                            <div class="
                                    mb-3
                                    flex
                                    items-center
                                    gap-3
                                ">
                                <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                    {{ sectionNumber(section) }}
                                </AppText>

                                <span class="
                                        h-px
                                        w-8
                                        bg-border-light
                                        dark:bg-border-dark
                                    " />
                            </div>

                            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" leading="tight">
                                {{ section.title }}
                            </AppHeading>

                            <AppText v-if="section.subtitle" tag="p" font="lora" size="base" color="muted"
                                leading="relaxed" class="mt-2">
                                {{ section.subtitle }}
                            </AppText>
                        </header>


                        <!-- Section Content -->

                        <div v-if="section.content" class="
                                mb-8
                                whitespace-pre-line
                                text-base
                                leading-8
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
                                )}`" v-else-if="
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
                                        pl-5
                                    ">
                                    <blockquote class="
                                            font-lora
                                            text-lg
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
                                            mt-3
                                            text-sm
                                            text-content-lightMuted
                                            dark:text-content-darkMuted
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
                                    <img v-if="blockContent(block).url" :src="stringValue(
                                        blockContent(block).url,
                                    )
                                        " :alt="stringValue(
                                            blockContent(block).alt ||
                                            block.title ||
                                            report.data.title,
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
                                            text-content-lightMuted
                                            dark:text-content-darkMuted
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
                                                        px-3
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
                                                        px-3
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
                                        py-7
                                        dark:border-border-dark
                                    ">
                                    <div class="
                                            font-prata
                                            text-4xl
                                            font-normal
                                            leading-none
                                            sm:text-5xl
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
                                        p-5
                                        dark:border-border-dark
                                    ">
                                    <AppText v-if="block.title" tag="h3" size="sm" weight="bold">
                                        {{ block.title }}
                                    </AppText>

                                    <div class="
                                            mt-5
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
                                                grid-cols-[minmax(90px,1fr)_2fr_auto]
                                                items-center
                                                gap-3
                                                text-sm
                                            ">
                                            <span>
                                                {{ label }}
                                            </span>

                                            <div class="
                                                    h-2
                                                    overflow-hidden
                                                    bg-muted
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
                                                    text-content-lightMuted
                                                    dark:text-content-darkMuted
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
                                        pl-5
                                    ">
                                    <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                        Key Finding
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block).finding
                                    " font="lora" size="lg" leading="relaxed" class="mt-2">
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
                                        p-5
                                        dark:border-border-dark
                                    ">
                                    <AppText size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                        Recommendation
                                    </AppText>

                                    <AppText v-if="
                                        blockContent(block)
                                            .recommendation
                                    " font="lora" size="lg" leading="relaxed" class="mt-2">
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
                                                blockContent(block)
                                                    .rationale,
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
                                " size="sm" color="muted" leading="relaxed" class="mt-3">
                                    {{ block.description }}
                                </AppText>
                            </article>
                        </div>
                    </section>
                </div>


                <!-- ==================================================== -->
                <!-- No Content -->
                <!-- ==================================================== -->

                <section v-else-if="
                    !report.data.description &&
                    !report.data.summary
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


    <!-- ================================================================ -->
    <!-- Related Reports -->
    <!-- ================================================================ -->

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
                sm:px-6
                sm:py-14
                lg:px-8
                lg:py-16
            ">
            <div class="mb-8">
                <AppText tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-2">
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
                <ReportCard v-for="relatedReport in related" :key="relatedreport.data.id" :report="relatedReport" :href="reportShow(
                    relatedreport.data.slug,
                ).url
                    " />
            </div>
        </div>
    </section>
</template>