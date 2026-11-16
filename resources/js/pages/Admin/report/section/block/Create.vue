
<script setup lang="ts">
import { computed, ref } from 'vue'
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
    store,
} from '@/routes/admin/report/sections/blocks'

import type { FormOption } from '@/types'

interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        id: number
        title: string
    }

    typeOptions: FormOption[]
}

const { report, section, typeOptions } = defineProps<Props>()

const content = ref('{\n  "text": ""\n}')
const jsonError = ref<string | null>(null)

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

const initialType = computed(() => typeOptions[0]?.value ?? '')

const indexUrl = computed(() =>
    index({
        report: report.id,
        section: section.id,
    })
)

const storeUrl = store.form({
    report: report.id,
    section: section.id,
})
</script>

<template>
    <Head :title="`Add Content Block - ${section.title}`" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4 border-b py-5">
            <BackButton />

            <Heading
                title="Add Content Block"
                :description="`${report.title} / ${section.title}`"
            />
        </div>

        <Form
            v-slot="{ errors, processing }"
            v-bind="storeUrl"
            :options="{
                preserveScroll: true,
            }"
            :transform="(data) => ({
                ...data,
                position: Number(data.position ?? 1),
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
                                description="Define the content block and its structured data."
                            />
                        </div>

                        <div class="space-y-5 p-6">
                            <AppFormControl
                                label="Type"
                                :error="errors.type"
                                required
                            >
                                <AppSelect
                                    name="type"
                                    :options="typeOptions"
                                    :default-value="initialType"
                                    placeholder="Select block type"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Title"
                                :error="errors.title"
                            >
                                <AppInput
                                    name="title"
                                    placeholder="Enter block title"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Description"
                                :error="errors.description"
                            >
                                <AppTextarea
                                    name="description"
                                    placeholder="Enter a short description..."
                                    :rows="4"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Content"
                                :error="errors.content"
                                description="Enter a valid JSON object representing this block's content."
                                required
                            >
                                <AppTextarea
                                    v-model="content"
                                    name="content"
                                    placeholder='{"text": "Write content here..."}'
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
                                description="Specify the display order of this block."
                            >
                                <AppInput
                                    name="position"
                                    type="number"
                                    min="1"
                                    :default-value="1"
                                />
                            </AppFormControl>
                        </div>
                    </section>

                    <section class="border bg-background p-6">
                        <Heading
                            title="Content Block"
                            description="Create a structured content block inside this report section."
                        />

                        <div class="mt-6 space-y-3">
                            <Button
                                type="submit"
                                class="w-full"
                                :disabled="processing"
                            >
                                {{ processing ? 'Creating...' : 'Create Block' }}
                            </Button>

                            <Button
                                type="button"
                                variant="outline"
                                class="w-full"
                                as-child
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