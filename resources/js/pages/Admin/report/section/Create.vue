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
    store,
} from '@/routes/admin/report/sections'


interface Props {
    report: {
        id: number
        title: string
    }
}

defineProps<Props>()
</script>


<template>

    <Head :title="`Add Section - ${report.title}`" />

    <div class="space-y-6">

        <!-- Header -->

        <div class="flex gap-4 py-5">
            <BackButton />

            <Heading title="Add Report Section" :description="report.title" />
        </div>


        <!-- Form -->

        <Form v-slot="{ errors, processing }" v-bind="store.form({
            report: report.id,
        })" :options="{
                preserveScroll: true,
            }" class="space-y-6">

            <div class="rounded-lg border bg-background p-6">

                <div class="mb-6">
                    <h2 class="text-base font-semibold">
                        Section Information
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Add a section to structure the report content.
                    </p>
                </div>


                <div class="space-y-5">

                    <!-- Title -->

                    <AppFormControl label="Title" :error="errors.title" required>
                        <AppInput name="title" placeholder="Enter section title" />
                    </AppFormControl>


                    <!-- Subtitle -->

                    <AppFormControl label="Subtitle" :error="errors.subtitle">
                        <AppInput name="subtitle" placeholder="Enter section subtitle" />
                    </AppFormControl>


                    <!-- Content -->

                    <AppFormControl label="Content" :error="errors.content">
                        <AppTextarea name="content" placeholder="Write the section content..." rows="14" />
                    </AppFormControl>


                    <!-- Position -->

                    <AppFormControl label="Position" :error="errors.position"
                        description="Lower numbers appear first in the report.">
                        <AppInput name="position" type="number" min="0" value="0" />
                    </AppFormControl>

                </div>
            </div>


            <!-- Actions -->

            <div class="flex justify-end gap-3">

                <Button type="button" variant="outline" as-child>
                    <Link :href="index({
                        report: report.id,
                    })
                        ">
                        Cancel
                    </Link>
                </Button>


                <Button type="submit" :disabled="processing">
                    {{
                        processing
                            ? 'Processing...'
                            : 'Create Section'
                    }}
                </Button>

            </div>

        </Form>
    </div>
</template>