<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3'

import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Button from '@/components/ui/button/Button.vue'

import {
    index,
    update,
} from '@/routes/admin/report/sections'
import { ReportSection } from '@/types'

interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        data: ReportSection
    }
}

const props = defineProps<Props>()
</script>

<template>
    <Head :title="`Edit Section - ${props.report.title}`" />

    <div class="space-y-6">
        <!-- Header -->

        <div class="flex gap-4 py-5">
            <BackButton />

            <Heading
                title="Edit Report Section"
                :description="props.report.title"
            />
        </div>

        <!-- Form -->

        <Form
            v-slot="{ errors, processing }"
            v-bind="
                update.form({
                    report: props.report.id,
                    section: props.section.data.id,
                })
            "
            :options="{
                preserveScroll: true,
            }"
            :reset-on-success="false"
            class="space-y-6"
        >
            <div>
                <div class="mb-6">
                    <Heading
                        title="Section Information"
                        description="Update the report section information."
                    />
                </div>

                <div class="space-y-5">
                    <!-- Title -->

                    <AppFormControl
                        label="Title"
                        :error="errors.title"
                        required
                    >
                        <AppInput
                            name="title"
                            placeholder="Enter section title"
                            :default-value="props.section.data.title"
                        />
                    </AppFormControl>

                    <!-- Subtitle -->

                    <AppFormControl
                        label="Subtitle"
                        :error="errors.subtitle"
                    >
                        <AppInput
                            name="subtitle"
                            placeholder="Enter section subtitle"
                            :default-value="
                                props.section.data.subtitle ?? ''
                            "
                        />
                    </AppFormControl>

                    <!-- Content -->

                    <AppFormControl
                        label="Content"
                        :error="errors.content"
                    >
                        <AppTextarea
                            name="content"
                            placeholder="Write the section content..."
                            :rows="14"
                            :default-value="
                                props.section.data.content ?? ''
                            "
                        />
                    </AppFormControl>

                    <!-- Position -->

                    <AppFormControl
                        label="Position"
                        :error="errors.position"
                        description="Lower numbers appear first in the report."
                    >
                        <AppInput
                            name="position"
                            type="number"
                            min="0"
                            :default-value="
                                props.section.data.position
                            "
                        />
                    </AppFormControl>
                </div>
            </div>

            <!-- Actions -->

            <div class="flex justify-end gap-3">
                <Button
                    type="button"
                    variant="outline"
                    as-child
                >
                    <Link
                        :href="
                            index({
                                report: props.report.id,
                            }).url
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
                            : 'Update Section'
                    }}
                </Button>
            </div>
        </Form>
    </div>
</template>