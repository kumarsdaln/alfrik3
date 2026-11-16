
<script setup lang="ts">
import { computed, ref, watch } from 'vue'
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
import { FormOption } from '@/types'

interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        id: number
        title: string
    }

    block: {
        data: ReportContentBlock
    }
    typeOptions: FormOption[]
}

const props = defineProps<Props>()

const content = ref(
    JSON.stringify(props.block.data.content ?? {}, null, 4)
)

const jsonError = ref<string | null>(null)

const selectedType = ref(props.block.data.type.value)

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
    "text": "Quote text",
    "attribution": "Author Name"
}`

        case 'image':
            return `{
    "url": "https://example.com/image.jpg",
    "alt": "Image description",
    "caption": "Image caption"
}`

        case 'table':
            return `{
    "headers": ["Year", "Revenue"],
    "rows": [
        ["2024", "₹10 Cr"],
        ["2025", "₹15 Cr"]
    ]
}`

        case 'statistic':
            return `{
    "value": "72%",
    "label": "Consumers prefer sustainable products",
    "context": "Supporting context"
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
    "implication": "What this finding means"
}`

        case 'recommendation':
            return `{
    "recommendation": "Recommended action",
    "actions": ["Action one", "Action two"],
    "priority": "high"
}`

        default:
            return '{}'
    }
}

const validateContent = (): boolean => {
    try {
        const parsed = JSON.parse(content.value)

        if (
            parsed === null ||
            Array.isArray(parsed) ||
            typeof parsed !== 'object'
        ) {
            jsonError.value = 'Content must be a valid JSON object.'
            return false
        }

        jsonError.value = null

        return true
    } catch {
        jsonError.value = 'Invalid JSON. Please check your JSON syntax.'

        return false
    }
}

const selectedTypeLabel = computed(
    () =>
        props.typeOptions.find(
            option => option.value === selectedType.value
        )?.label ?? props.block.data.type.label
)

const indexUrl = computed(() =>
    index({
        report: props.report.id,
        section: props.section.id,
    })
)

const updateForm = update.form({
    report: props.report.id,
    section: props.section.id,
    block: props.block.data.id,
})

watch(selectedType, (newType, oldType) => {
    if (newType !== oldType) {
        // Preserve existing content when changing the block type.
        // The placeholder changes, but stored content is not discarded.
        jsonError.value = null
    }
})
</script>

<template>
    <Head :title="`Edit Content Block - ${section.title}`" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 border-b py-5">
            <BackButton />

            <Heading
                title="Edit Content Block"
                :description="`${report.title} / ${section.title}`"
            />
        </div>

        <Form
            v-slot="{ errors, processing }"
            v-bind="updateForm"
            :options="{
                preserveScroll: true,
            }"
            :transform="data => ({
                ...data,
                position: Number(data.position),
                content: JSON.parse(String(data.content)),
            })"
            @before="validateContent()"
            class="space-y-6"
        >
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main -->
                <div class="space-y-6 lg:col-span-2">
                    <section class="border bg-background">
                        <div class="border-b p-6">
                            <Heading
                                title="Block Information"
                                description="Update the content block and its structured data."
                            />
                        </div>

                        <div class="space-y-5 p-6">
                            <AppFormControl
                                label="Type"
                                :error="errors.type"
                                required
                            >
                                <AppSelect
                                    v-model="selectedType"
                                    name="type"
                                    :options="typeOptions"
                                    placeholder="Select block type"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Title"
                                :error="errors.title"
                            >
                                <AppInput
                                    name="title"
                                    :default-value="block.data.title ?? ''"
                                    placeholder="Enter block title"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Description"
                                :error="errors.description"
                            >
                                <AppTextarea
                                    name="description"
                                    :default-value="block.data.description ?? ''"
                                    placeholder="Enter a short description..."
                                    :rows="4"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Content"
                                :error="errors.content"
                                description="Edit the structured JSON content for this block.data."
                                required
                            >
                                <AppTextarea
                                    v-model="content"
                                    name="content"
                                    :placeholder="contentPlaceholder(selectedType)"
                                    :rows="18"
                                    class="font-mono text-sm"
                                />
                            </AppFormControl>

                            <p
                                v-if="jsonError"
                                class="text-sm text-destructive"
                            >
                                {{ jsonError }}
                            </p>
                        </div>
                    </section>
                </div>

                <!-- Sidebar -->
                <aside class="space-y-6">
                    <!-- Position -->
                    <section class="border bg-background">
                        <div class="border-b p-6">
                            <Heading
                                title="Position"
                                description="Lower numbers appear first."
                            />
                        </div>

                        <div class="p-6">
                            <AppFormControl
                                label="Position"
                                :error="errors.position"
                            >
                                <AppInput
                                    name="position"
                                    type="number"
                                    min="1"
                                    :default-value="block.data.position"
                                />
                            </AppFormControl>
                        </div>
                    </section>

                    <!-- Selected Block -->
                    <section class="border bg-background p-6">
                        <Heading
                            title="Selected Block"
                            description="Current block type information."
                        />

                        <div class="mt-5 border bg-muted/30 px-4 py-3">
                            <p class="text-sm font-medium">
                                {{ selectedTypeLabel }}
                            </p>

                            <p class="mt-1 text-xs text-muted-foreground">
                                {{ selectedType }}
                            </p>
                        </div>
                    </section>

                    <!-- Actions -->
                    <section class="border bg-background p-6">
                        <div class="space-y-3">
                            <Button
                                type="submit"
                                class="w-full"
                                :disabled="processing"
                            >
                                {{ processing ? 'Updating...' : 'Update Block' }}
                            </Button>

                            <Button
                                type="button"
                                variant="outline"
                                as-child
                                class="w-full"
                            >
                                <Link :href="indexUrl">
                                    Cancel
                                </Link>
                            </Button>
                        </div>
                    </section>
                </aside>
            </div>
        </Form>
    </div>
</template>
