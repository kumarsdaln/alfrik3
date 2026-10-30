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

defineProps<Props>()
</script>

<template>
    <Head :title="`Add Content Block - ${section.title}`" />

    <div class="space-y-6">
        <div class="flex items-center gap-3">
            <BackButton />

            <Heading
                title="Add Content Block"
                :description="`${report.title} / ${section.title}`"
            />
        </div>

        <Form
            v-slot="{ errors, processing }"
            v-bind="store.form({
                report: report.id,
                section: section.id,
            })"
            :options="{
                preserveScroll: true,
            }"
            :transform="(data) => ({
                ...data,
                position: Number(data.position ?? 0),
                content: data.content
                    ? JSON.parse(data.content as string)
                    : {},
            })"
            class="space-y-6"
        >
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main -->
                <div class="space-y-6 lg:col-span-2">
                    <div>
                        <Heading
                            title="Block Information"
                            description="Define the content block and its data."
                        />

                        <div class="mt-6 space-y-5">
                            <AppFormControl
                                label="Type"
                                :error="errors.type"
                                required
                            >
                                <AppSelect
                                    name="type"
                                    :options="typeOptions"
                                    :default-value="typeOptions[0]?.value"
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
                                    rows="4"
                                />
                            </AppFormControl>

                            <AppFormControl
                                label="Content"
                                :error="errors.content"
                                description="Enter valid JSON content for this block."
                                required
                            >
                                <AppTextarea
                                    name="content"
                                    :default-value="'{}'"
                                    :placeholder="`{
    &quot;text&quot;: &quot;Write the content here...&quot;
}`"
                                    :rows="18"
                                    class="font-mono text-sm"
                                />
                            </AppFormControl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div>
                        <Heading
                            title="Position"
                            description="Lower numbers appear first."
                        />

                        <div class="mt-6">
                            <AppFormControl
                                label="Position"
                                :error="errors.position"
                            >
                                <AppInput
                                    name="position"
                                    type="number"
                                    min="0"
                                    :default-value="0"
                                />
                            </AppFormControl>
                        </div>
                    </div>

                    <div>
                        <Heading
                            title="Content Block"
                            description="Choose a block type and provide its content."
                        />
                    </div>

                    <div class="flex justify-end gap-3">
                        <Button
                            type="button"
                            variant="outline"
                            as-child
                        >
                            <Link
                                :href="index({
                                    report: report.id,
                                    section: section.id,
                                })"
                            >
                                Cancel
                            </Link>
                        </Button>

                        <Button
                            type="submit"
                            :disabled="processing"
                        >
                            {{ processing ? 'Processing...' : 'Create Block' }}
                        </Button>
                    </div>
                </div>
            </div>
        </Form>
    </div>
</template>