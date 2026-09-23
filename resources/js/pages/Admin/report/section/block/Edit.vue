<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3'

import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Button from '@/components/ui/button/Button.vue'

import {
    index,
    update,
} from '@/routes/admin/report/sections/blocks'

import type { ReportContentBlock } from '@/types/report'


interface Option {
    value: string
    label: string
}


interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        id: number
        title: string
    }

    block: ReportContentBlock

    typeOptions: Option[]
}


const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Content Placeholder
|--------------------------------------------------------------------------
*/

const contentPlaceholder = (type: string): string => {
    switch (type) {
        case 'text':
            return `{
    "text": "Write the content here..."
}`

        case 'heading':
            return `{
    "level": 2,
    "text": "Section Heading"
}`

        case 'quote':
            return `{
    "quote": "Quote text",
    "author": "Author Name",
    "source": "Source"
}`

        case 'image':
            return `{
    "url": "https://example.com/image.jpg",
    "alt": "Image description",
    "caption": "Image caption"
}`

        case 'table':
            return `{
    "columns": ["Year", "Revenue"],
    "rows": [
        ["2024", "₹10 Cr"],
        ["2025", "₹15 Cr"]
    ]
}`

        case 'statistic':
            return `{
    "value": "72%",
    "label": "Consumers prefer sustainable products",
    "source": "Alfrik Research"
}`

        case 'chart':
            return `{
    "chart_type": "bar",
    "labels": ["2023", "2024", "2025"],
    "datasets": [
        {
            "label": "Revenue",
            "data": [10, 14, 19]
        }
    ]
}`

        case 'finding':
            return `{
    "finding": "Key research finding",
    "evidence": "Supporting evidence"
}`

        case 'recommendation':
            return `{
    "recommendation": "Recommended action",
    "rationale": "Why this action is recommended"
}`

        default:
            return '{}'
    }
}


const initialContent = props.block.content
    ? JSON.stringify(
        props.block.content,
        null,
        4,
    )
    : '{}'
</script>


<template>
    <Head
        :title="`Edit Content Block - ${section.title}`"
    />

    <div class="space-y-6">

        <!-- ============================================================= -->
        <!-- Header -->
        <!-- ============================================================= -->

        <div class="flex items-center gap-3">
            <BackButton />

            <Heading
                title="Edit Content Block"
                :description="
                    `${report.title} / ${section.title}`
                "
            />
        </div>


        <!-- ============================================================= -->
        <!-- Form -->
        <!-- ============================================================= -->

        <Form
            v-slot="{ errors, processing }"
            v-bind="update.form({
                report: props.report.id,
                section: props.section.id,
                block: props.block.id,
            })"
            :options="{
                preserveScroll: true,
            }"
            class="space-y-6"
        >
            <div class="grid gap-6 lg:grid-cols-3">

                <!-- ===================================================== -->
                <!-- Main -->
                <!-- ===================================================== -->

                <div class="space-y-6 lg:col-span-2">

                    <div
                        class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        "
                    >
                        <div class="mb-6">
                            <h2 class="text-base font-semibold">
                                Block Information
                            </h2>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-muted-foreground
                                "
                            >
                                Update the content block.
                            </p>
                        </div>


                        <div class="space-y-5">

                            <!-- Type -->

                            <AppFormControl
                                label="Type"
                                :error="errors.type"
                                required
                            >
                                <AppSelect
                                    name="type"
                                    :options="typeOptions"
                                    :default-value="
                                        block.type.value
                                    "
                                    placeholder="Select block type"
                                />
                            </AppFormControl>


                            <!-- Title -->

                            <AppFormControl
                                label="Title"
                                :error="errors.title"
                            >
                                <AppInput
                                    name="title"
                                    :default-value="
                                        block.title ?? ''
                                    "
                                    placeholder="Enter block title"
                                />
                            </AppFormControl>


                            <!-- Description -->

                            <AppFormControl
                                label="Description"
                                :error="errors.description"
                            >
                                <AppTextarea
                                    name="description"
                                    :default-value="
                                        block.description ?? ''
                                    "
                                    placeholder="Enter a short description..."
                                    rows="4"
                                />
                            </AppFormControl>


                            <!-- Content -->

                            <AppFormControl
                                label="Content"
                                :error="errors.content"
                                description="Content is stored as JSON for this block."
                                required
                            >
                                <AppTextarea
                                    name="content"
                                    :default-value="initialContent"
                                    :placeholder="
                                        contentPlaceholder(
                                            block.type.value,
                                        )
                                    "
                                    rows="18"
                                    class="font-mono text-sm"
                                />
                            </AppFormControl>

                        </div>
                    </div>
                </div>


                <!-- ===================================================== -->
                <!-- Sidebar -->
                <!-- ===================================================== -->

                <div class="space-y-6">

                    <!-- Position -->

                    <div
                        class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        "
                    >
                        <div class="mb-6">
                            <h2 class="text-base font-semibold">
                                Position
                            </h2>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-muted-foreground
                                "
                            >
                                Lower numbers appear first.
                            </p>
                        </div>

                        <AppFormControl
                            label="Position"
                            :error="errors.position"
                        >
                            <AppInput
                                name="position"
                                type="number"
                                min="0"
                                :default-value="
                                    block.position
                                "
                            />
                        </AppFormControl>
                    </div>


                    <!-- Selected Block -->

                    <div
                        class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        "
                    >
                        <h2 class="text-base font-semibold">
                            Selected Block
                        </h2>

                        <div class="mt-4">
                            <div
                                class="
                                    rounded-md
                                    border
                                    bg-muted/30
                                    px-4
                                    py-3
                                "
                            >
                                <p class="text-sm font-medium">
                                    {{
                                        typeOptions.find(
                                            option =>
                                                option.value ===
                                                block.type.value,
                                        )?.label ??
                                        block.type.label
                                    }}
                                </p>

                                <p
                                    class="
                                        mt-1
                                        text-xs
                                        text-muted-foreground
                                    "
                                >
                                    {{ block.type.value }}
                                </p>
                            </div>
                        </div>
                    </div>


                    <!-- Actions -->

                    <div
                        class="
                            rounded-lg
                            border
                            bg-background
                            p-6
                        "
                    >
                        <div class="flex justify-end gap-3">

                            <Button
                                type="button"
                                variant="outline"
                                as-child
                            >
                                <Link
                                    :href="
                                        index({
                                            report: report.id,
                                            section: section.id,
                                        })
                                    "
                                >
                                    Cancel
                                </Link>
                            </Button>

                            <Button
                                type="submit"
                                :disabled="processing"
                            >
                                {{
                                    processing
                                        ? 'Processing...'
                                        : 'Update Block'
                                }}
                            </Button>

                        </div>
                    </div>

                </div>
            </div>
        </Form>
    </div>
</template>