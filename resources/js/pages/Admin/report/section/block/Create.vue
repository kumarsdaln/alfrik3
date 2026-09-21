<script setup lang="ts">
    import { Head, router, useForm } from '@inertiajs/vue3'
    import { ArrowLeft } from '@lucide/vue'
    import { computed, ref } from 'vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import {
        index,
        store,
    } from '@/routes/admin/report/sections/blocks'

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

        typeOptions: Option[]
    }

    const props = defineProps<Props>()

    const form = useForm({
        type: props.typeOptions[0]?.value ?? 'text',
        title: '',
        description: '',
        content: '',
        position: 0,
    })

    const jsonError = ref<string | null>(null)

    const selectedType = computed(() => {
        return props.typeOptions.find(
            (option) => option.value === form.type,
        )
    })

    const contentPlaceholder = computed(() => {
        switch (form.type) {
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
    })

    const validateContent = (): Record<string, unknown> | null => {
        jsonError.value = null

        if (!form.content.trim()) {
            return {}
        }

        try {
            const parsed = JSON.parse(form.content)

            if (
                parsed === null ||
                typeof parsed !== 'object' ||
                Array.isArray(parsed)
            ) {
                jsonError.value =
                    'Content must be a JSON object.'

                return null
            }

            return parsed
        } catch {
            jsonError.value =
                'Content contains invalid JSON.'

            return null
        }
    }

    const submit = () => {
        const content = validateContent()

        if (content === null) {
            return
        }

        form.transform((data) => ({
            ...data,
            content,
        }))

        form.post(
            store.url({
                report: props.report.id,
                section: props.section.id,
            }),
        )
    }

    const back = () => {
        router.visit(
            index.url({
                report: props.report.id,
                section: props.section.id,
            }),
        )
    }
</script>

<template>

    <Head :title="`Add Content Block - ${section.title}`" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <AppButton variant="ghost" size="icon" type="button" @click="back">
                    <ArrowLeft class="size-4" />
                </AppButton>

                <Heading title="Add Content Block" :description="`${report.title} / ${section.title}`" />
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="space-y-6 lg:col-span-2">
                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Block Information
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Define the content block and its data.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <AppFormControl label="Type" :error="form.errors.type" required>
                                    <AppSelect v-model="form.type" :options="typeOptions"
                                        placeholder="Select block type" />
                                </AppFormControl>

                                <AppFormControl label="Title" :error="form.errors.title">
                                    <AppInput v-model="form.title" name="title" placeholder="Enter block title" />
                                </AppFormControl>

                                <AppFormControl label="Description" :error="form.errors.description">
                                    <AppTextarea v-model="form.description" name="description"
                                        placeholder="Enter a short description..." rows="4" />
                                </AppFormControl>

                                <AppFormControl label="Content" :error="form.errors.content ||
                                    jsonError
                                    " :description="`Content format for ${selectedType?.label ?? 'this block'}.`
                                        " required>
                                    <AppTextarea v-model="form.content" name="content" :placeholder="contentPlaceholder"
                                        rows="18" class="font-mono text-sm" />
                                </AppFormControl>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Position
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Lower numbers appear first.
                                </p>
                            </div>

                            <AppFormControl label="Position" :error="form.errors.position">
                                <AppInput v-model.number="form.position" type="number" name="position" min="0" />
                            </AppFormControl>
                        </div>

                        <div class="rounded-lg border bg-background p-6">
                            <h2 class="text-base font-semibold">
                                Selected Block
                            </h2>

                            <div class="mt-4">
                                <div class="rounded-md border bg-muted/30 px-4 py-3">
                                    <p class="text-sm font-medium">
                                        {{ selectedType?.label }}
                                    </p>

                                    <p class="mt-1 text-xs text-muted-foreground">
                                        {{ form.type }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border bg-background p-6">
                            <div class="flex justify-end gap-3">
                                <AppButton type="button" variant="outline" @click="back">
                                    Cancel
                                </AppButton>

                                <AppButton type="submit" :disabled="form.processing">
                                    Create Block
                                </AppButton>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </TableLayout>
</template>